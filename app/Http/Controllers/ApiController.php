<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $appconfig = [];

    public function __construct()
    {

        //先清除5分鐘前的lock
        $lockTime = time() - (60*5);
        DB::table('coupon_data')
            ->whereRaw("STR_TO_DATE(cpLockTime, '%Y-%m-%d %H:%i:%s') <= DATE_SUB(NOW(), INTERVAL 5 MINUTE)")
            ->where('cpTake', '=', 0)
            ->update(['cpUserID' => null, 'cpLock' => 0, 'cpLockTime' => null, 'cpStatus' => 'N']);


        $configData = DB::table('app_config')->get();
        if ($configData) {
            foreach ($configData as $k => $v) {
                $key = $v->app_key;
                $val = $v->app_val;

                $this->appconfig[$key] = $val;
            }
            $todayMax = 'DAILY'.date("nd").'_MAX_MILK';
            if (isset($this->appconfig[$todayMax])) {
                $this->appconfig['DAILY_MAX_MILK'] = $this->appconfig[$todayMax];
            }
        }
    }

    public function NearStore(Request $request)
    {
        $fields = $this->validate($request, [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        $branchData = DB::table('brand_branch')
                ->selectRaw('bbID as id, bbName as name, bbAddress as addr, bbLat as lat, bbLng as lng,
                (
                    3959 *
                    acos(cos(radians(?)) *
                    cos(radians(bbLat)) *
                    cos(radians(bbLng) -
                    radians(?)) +
                    sin(radians(?)) *
                    sin(radians(bbLat)))
                ) AS distance', array( $fields['lat'], $fields['lng'], $fields['lat'] ))
                ->where('bbBrand', '=', 1)
                ->orderBy('distance', 'ASC')
                ->first();
        if ($branchData) {
            return response()->json([
                'success' => true,
                'data' => $branchData,
            ]);
        }
    }


    public function GameApply(Request $request)
    {
        if ($request->isJson()) {
            //dd($request->ip());


            $LINE_CLIENTID = env('LINE_CLIENTID');
            log::DEBUG($request->json()->all());
            $userId = $request->json()->get('userId');
            $access_token = $request->json()->get('access_token');
            $id_token = $request->json()->get('id_token');
            if ($userId && $access_token && $id_token) {
                $response = Http::asForm()->post(env('LINE_IDVERIFY_API'), [
                    'id_token' => $id_token,
                    'client_id' => $LINE_CLIENTID
                ]);
                $LINEData = $response->json();
                if (isset($LINEData['error'])) {
                    return response()->json(['success' => 0, 'message' => '帳戶異常，請重新授權。code'.__LINE__]);
                }
                if ($LINEData['sub'] != $userId) {
                    return response()->json(['success' => 0, 'message' => 'USERID異常。code'.__LINE__]);
                } else {
                    //紀錄App log
                    DB::table('app_log')->insert([
                        'userID' => $userId,
                        'logName' => 'GameApply',
                        'logIP' => $request->ip()
                    ]);
                    //判斷LINE Friend
                    $friend_response = Http::withHeaders([
                        'Authorization' => 'Bearer '.$access_token
                    ])->get(env('LINE_FRIEND_API'));
                    $LINEFriend = $friend_response->json();
                    $userFriend = ($LINEFriend['friendFlag'] == true)? 1:0;
                    //儲存用戶資料
                    if (DB::table('user_data')->where('userID', $userId)->exists()) {
                        DB::table('user_data')
                            ->where('userID', $userId)
                            ->update(
                                [
                                    'userName' => $LINEData['name'],
                                    'userAccessToken' => $access_token,
                                    'userIdToken' => $id_token,
                                    'userOA' => $userFriend,
                                ]
                            );
                    } else {
                        DB::table('user_data')
                            ->insert(
                                [
                                    'userID' => $userId,
                                    'userName' => $LINEData['name'],
                                    'userAccessToken' => $access_token,
                                    'userIdToken' => $id_token,
                                    'userOA' => $userFriend,
                                ]
                            );
                    }

                    //獎項是否已經送完
                    $couponUsed = DB::table('coupon_data')
                                    ->where('cpTake', '=', 1)
                                    ->orWhere('cpLock', '=', 1)
                                    ->count();
                    if ($couponUsed >= $this->appconfig['MAX_MILK']) {
                        return response()->json(['success' => 0, 'message' => '活動額度已發送完畢']);
                    } else {
                        //本日額度
                        $couponDailyUsed = DB::table('coupon_data')
                                    ->where(function ($query) {
                                        $query->whereRaw("cpTake=1")
                                            ->whereRaw("DATE_FORMAT(cpTakeTime,'%Y-%m-%d') = '".date('Y-m-d')."'");
                                    })
                                    ->orWhere(function ($query) {
                                        $query->whereRaw("cpLock=1")
                                            ->whereRaw("DATE_FORMAT(cpLockTime,'%Y-%m-%d') = '".date('Y-m-d')."'");
                                    })
                                    ->count();
                        /*$couponDailyUsed = DB::table('coupon_data')
                                        ->where(DB::raw("
                                            ( cpTake=1 AND DATE_FORMAT(cpTakeTime,'%Y-%m-%d')='".date('Y-m-d')."' ) OR ( cpLock=1 AND DATE_FORMAT(cpLockTime,'%Y-%m-%d')='".date('Y-m-d')."')
                                        "))->count();*/
                        log::DEBUG($couponDailyUsed . '|' . $this->appconfig['DAILY_MAX_MILK']);
                        if ($couponDailyUsed >= $this->appconfig['DAILY_MAX_MILK']) {
                            return response()->json(['success' => 0, 'message' => '本日活動額度已發送完畢']);
                        } else {
                            $couponDailyLimit = $this->appconfig['DAILY_MAX_MILK'] - $couponDailyUsed;
                            //檢查賬戶本日額度
                            $couponUserUsed = DB::table('coupon_data')
                                                ->where('cpUserID', '=', $userId)
                                                ->where('cpTake', '=', 1)
                                                ->whereDate('cpTakeTime', '=', date('Y-m-d'))
                                                ->count();
                            if ($couponUserUsed > 0) {
                                if ($userFriend == 0) {
                                    return response()->json(['success' => 0, 'message' => '本日額度用完']);
                                } else {
                                    $couponOAUsed = DB::table('coupon_data')
                                                ->where('cpUserID', '=', $userId)
                                                ->where('cpTake', '=', 1)
                                                ->where('cpStatus', '=', 'O')
                                                ->count();
                                    if ($couponOAUsed > 0) {
                                        return response()->json(['success' => 0, 'message' => '好友額度用完']);
                                    } else {
                                        $data = $this->fnCouponGenerator($userId, $userFriend, $couponDailyLimit, 'O');
                                        if ($data) {
                                            return response()->json(['success' => 1, 'data' => $data]);
                                        } else {
                                            return response()->json(['success' => 0, 'message' => '謝謝參與' . __LINE__]);
                                        }
                                    }
                                }
                            } else {
                                $data = $this->fnCouponGenerator($userId, $userFriend, $couponDailyLimit, 'N');
                                if ($data) {
                                    return response()->json(['success' => 1, 'data' => $data]);
                                } else {
                                    return response()->json(['success' => 0, 'message' => '謝謝參與'. __LINE__]);
                                }
                            }
                        }
                    }
                }
            } else {
                return response()->json(['success' => 0, 'message' => '異常存取'.__LINE__]);
            }
        } else {
            return response()->json(['success' => 0, 'message' => '異常存取']);
        }
    }


    public function GameResult(Request $request)
    {
        if ($request->isJson()) {
            $status = $request->json()->get('status');
            $userId = $request->json()->get('userId');
            $access_token = $request->json()->get('access_token');
            $id_token = $request->json()->get('id_token');
            $LINE_CLIENTID = env('LINE_CLIENTID');

            if ($status && $userId && $access_token && $id_token) {
                //驗證id_token
                $response = Http::asForm()->post(env('LINE_IDVERIFY_API'), [
                    'id_token' => $id_token,
                    'client_id' => $LINE_CLIENTID
                ]);
                $LINEData = $response->json();
                if (isset($LINEData['error'])) {
                    return response()->json(['success' => 0, 'message' => '帳戶異常，請重新授權。code'.__LINE__]);
                }
                if ($LINEData['sub'] != $userId) {
                    return response()->json(['success' => 0, 'message' => 'USERID異常。code'.__LINE__]);
                } else {
                    //紀錄App log
                    DB::table('app_log')->insert([
                        'userID' => $userId,
                        'logName' => 'PrizeList',
                        'logIP' => $request->ip()
                    ]);

                    //取得最後一筆lock資料

                    $couponData = DB::table('coupon_data')->select("cpId", "cpLink", "cpType")
                                    ->where('cpTake', '=', '0')
                                    ->where('cpLock', '=', '1')
                                    ->where('cpUserID', '=', $userId)
                                    ->where('cpStatus', '=', $status)
                                    ->whereRaw("STR_TO_DATE(cpLockTime, '%Y-%m-%d %H:%i:%s') > DATE_SUB(NOW(), INTERVAL 5 MINUTE)")
                                    ->orderBy("cpLockTime", "desc")
                                    ->first();

                    if ($couponData) {
                        //領取
                        DB::table('coupon_data')->where('cpId', $couponData->cpId)
                        ->update([
                                'cpTake'=>'1',
                                'cpTakeTime'=>DB::raw('NOW()'),
                                'cpLock'=>'0',
                                'cpLockTime'=>null,
                                'cpUserID'=> $userId,
                                'cpIP' => $request->ip() ]);
                        $data['date'] = date("m/d");
                        $data['serial'] = $couponData->cpLink;
                        $data['type'] =  $couponData->cpType;
                        $data['id'] =  $couponData->cpId;


                        return response()->json(['success' => 1, 'data' => $data]);
                    } else {
                        return response()->json(['success' => 0, 'message' => '領取時間超時，請重新進入活動'.__LINE__]);
                    }
                }
            } else {
                return response()->json(['success' => 0, 'message' => '異常存取'.__LINE__]);
            }
        } else {
            return response()->json(['success' => 0, 'message' => '異常存取'.__LINE__]);
        }
    }


    public function PrizeList(Request $request)
    {
        if ($request->isJson()) {
            $userId = $request->json()->get('userId');
            $data = array();

            //紀錄App log
            DB::table('app_log')->insert([
                'userID' => $userId,
                'logName' => 'PrizeList',
                'logIP' => $request->ip()
            ]);



            $prizeAry = DB::table('coupon_data')->select("cpLink", "cpTakeTime", "cpStatus")
                        ->where("cpUserID", $userId)
                        ->where("cpTake", '1')
                        ->orderBy("cpTakeTime", "desc")
                        ->get();
            if ($prizeAry) {
                foreach ($prizeAry as $k => $v) {
                    $data[] = array(
                        'date' => date("m/d", strtotime($v->cpTakeTime)),
                        'status' => $v->cpStatus,
                        'serial' => $v->cpLink
                    );
                }
            }
            return response()->json(['success' => 1, 'data' => $data]);
        } else {
            return response()->json(['success' => 0, 'message' => '']);
        }
    }

    protected function fnCouponGenerator($userId, $userFriend, $couponLimit, $status)
    {
        //先判斷是否有五分內的lock獎項
        $reprizeData = DB::table('coupon_data')->select("cpId", "cpLink", "cpType")
                        ->where('cpTake', '=', '0')
                        ->where('cpLock', '=', '1')
                        ->where('cpUserID', '=', $userId)
                        ->whereRaw("STR_TO_DATE(cpLockTime, '%Y-%m-%d %H:%i:%s') > DATE_SUB(NOW(), INTERVAL 5 MINUTE)")
                        ->orderBy("cpLockTime", "desc")
                        ->first();
        if ($reprizeData) {
            $data['status'] = $status;
            $data['milk'] = $reprizeData->cpType;
            $data['is_oa'] = $userFriend;
            $data['cpId'] = $reprizeData->cpId;
            return $data;
        }
        $prizeLimit = round($couponLimit * ((date("H")*2)/100), 0);
        $prizeLimit = $couponLimit;
        $prizeData = DB::table('coupon_data')->select("cpId", "cpLink", "cpType")
                    ->where('cpTake', '=', '0')
                    ->where('cpLock', '=', '0')
                    ->inRandomOrder()
                    ->limit($prizeLimit)
                    ->get();
        if ($prizeData && count($prizeData) > 0) {
            $prizeAry = $prizeData->toArray();

            $couponLimit = $prizeLimit;

            for ($i=0; $i<($couponLimit); $i++) {
                if (isset($prizeAry[$i])) {
                    $mergeData[] = $prizeAry[$i];
                } else {
                    unset($tempData);
                    $tempData = new \stdClass();
                    $tempData->cpId = -1;
                    $tempData->cpLink = '';
                    $tempData->cpType = $status;
                    $mergeData[] = $tempData;
                }
                shuffle($mergeData);
            }
            $currentData = current($mergeData);

            //鎖定獎項
            if ($currentData->cpId > -1) {
                DB::table('coupon_data')->where('cpId', $currentData->cpId)
                    ->update(['cpLock'=>'1', 'cpLockTime'=>DB::raw('NOW()'), 'cpUserID'=> $userId, 'cpStatus'=>$status ]);
                $data['status'] = $status;
                $data['milk'] = $currentData->cpType;
                $data['is_oa'] = $userFriend;
                $data['cpId'] = $currentData->cpId;

                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
