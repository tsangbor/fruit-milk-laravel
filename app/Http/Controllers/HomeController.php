<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class HomeController extends Controller
{
    protected $appconfig = [];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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


        //$this->middleware('auth');
    }


    /**
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /*
        Permission::create(['guard_name' => 'admin', 'name' => 'admin.weeklymuji.*']);
        Permission::create(['guard_name' => 'admin', 'name' => 'admin.life.*']);
        Permission::create(['guard_name' => 'admin', 'name' => 'admin.column.*']);
        Permission::create(['guard_name' => 'admin', 'name' => 'admin.activity.*']);
        Permission::create(['guard_name' => 'admin', 'name' => 'admin.setting.*']);

        auth()->user()->givePermissionTo(['admin.weeklymuji.*', 'admin.life.*', 'admin.column.*', 'admin.activity.*', 'admin.setting.*']);*/
        //dd(auth()->user()->is_super);
        //全部抽獎數
        $report['ALL_MAX_MILK'] = DB::table('coupon_data')
                                        ->where(function ($query) {
                                            $query->whereRaw("cpTake=1");
                                        })->count();
        $UNI_MAX_MILK = DB::table('coupon_data')
                                        ->select("cpUserID")
                                        ->where('cpTake', 1)
                                        ->groupBy('cpUserID')
                                        ->get();
        $report['UNI_MAX_MILK'] = count($UNI_MAX_MILK);
        //當日總數
        $report['DAILY_MAX_MILK'] = $this->appconfig['DAILY_MAX_MILK'];
        $report['DAILY_MILK_TAKE'] = DB::table('coupon_data')
                                        ->where(function ($query) {
                                            $query->whereRaw("cpTake=1")
                                                ->whereRaw("DATE_FORMAT(cpTakeTime,'%Y-%m-%d') = '".date('Y-m-d')."'");
                                        })->count();
        $report['progressbar'] =  round(($report['DAILY_MILK_TAKE']/$report['DAILY_MAX_MILK'])*100);

        //品項發送比例
        $report['DAILY_MILK_TAKE_A'] = DB::table('coupon_data')
                                        ->where(function ($query) {
                                            $query->whereRaw("cpTake=1")
                                                ->whereRaw("cpType='A'");
                                        })->count();
        $report['DAILY_MILK_TAKE_PERCENT_A'] = (($report['DAILY_MILK_TAKE_A']/5000) *100);
        $report['DAILY_MILK_TAKE_B'] = DB::table('coupon_data')
                                        ->where(function ($query) {
                                            $query->whereRaw("cpTake=1")
                                                ->whereRaw("cpType='B'");
                                        })->count();
        $report['DAILY_MILK_TAKE_PERCENT_B'] = (($report['DAILY_MILK_TAKE_B']/5000) *100);


        return view('admin.dashboard_index', [
            'menu' => 'index',
            'breadcrumb' => array('主控台'),
            'report' => $report,
        ]);
    }

    public function event(Request $request)
    {
        return view('admin.event_index', [
            'menu' => 'dashboard',
            'breadcrumb' => array('主控台', '資料管理'),
            'quicklink' => array( 'title'=> '水果牛奶', 'link' => route('admin.dashboard') ),
            'list' =>  array( 'title'=>'資料管理' ) ,
        ]);
    }

    public function event_list(Request $request)
    {
        $datas['draw'] = $request->draw;
        $datas['data'] = array();
        $datas['recordsTotal'] = DB::table('coupon_data')->where("cpTake", "1")->count();
        if (sizeof($_GET['columns']) > 0) {
            $order = $_GET['order'][0]['column'];
            $sort = $_GET['order'][0]['dir'];
            switch ($order) {
                case '0':
                default:
                    $orderName = "coupon_data.cpId";
                break;
                case '1':
                    $orderName = "coupon_data.cpUserID";
                break;
                case '5':
                    $orderName = "coupon_data.cpTakeTime";
                break;

            }

            $where[] = array( 'coupon_data.cpTake', '=', "1" );

            if ($_GET['search']['value'] != '') {
                $txtSearch = @trim($_GET['search']['value']);
                $arySearch[] = " coupon_data.cpLink  LIKE '%$txtSearch%' ";
                $arySearch[] = " coupon_data.cpCode  LIKE '%$txtSearch%' ";
                $arySearch[] = " coupon_data.cpUserID  LIKE '%$txtSearch%' ";
                $arySearch[] = " coupon_data.cpTakeTime  LIKE '%$txtSearch%' ";
                $sqlSearch = " AND ( " . implode(" OR ", $arySearch) . ") ";


                $where[] = array( 'coupon_data.cpLink', 'like', "%$txtSearch%" );
                $where[] = array( 'coupon_data.cpLink', 'like', "%$txtSearch%" );
                $where[] = array( 'coupon_data.cpCode', 'like', "%$txtSearch%" );
                $where[] = array( 'coupon_data.cpUserID', 'like', "%$txtSearch%" );
                $where[] = array( 'coupon_data.cpTakeTime', 'like', "%$txtSearch%" );
            }

            $dataAry = DB::table('coupon_data');
            if (isset($where) && sizeof($where) > 0) {
                $dataAry = $dataAry->where($where);
            }
            $dataAry = $dataAry->orderBy($orderName, $sort)->offset($request->start)->limit($request->length)->get();
            if ($dataAry) {
                foreach ($dataAry as $k => $v) {
                    $aryTemp['cpId'] = $v->cpId;
                    $aryTemp['cpUserID'] = $v->cpUserID;
                    $aryTemp['cpLink'] = $v->cpLink;
                    $aryTemp['cpType'] = ($v->cpType == 'A')? '木瓜':'西瓜';
                    $aryTemp['cpStatus'] = ($v->cpStatus == 'N')? '正常':'好友';
                    $aryTemp['cpTakeTime'] = $v->cpTakeTime;

                    /*
                    $aryTemp['action'] = '<div class="btn-group mb-4 mr-2" role="group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 執行 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                    <div class="dropdown-menu" aria-labelledby="btnOutline">
                        <a href="javascript:void(0);" class="dropdown-item dropdown-btn-edit" data-id="'.$v->id.'"><i class="fas fa-edit mr-1"></i>編輯</a>';
                    $aryTemp['action'] .= '</div></div>';*/

                    $datas['data'][] = $aryTemp;
                    unset($aryTemp);
                }
            }
            $datas['recordsFiltered'] = ($_GET['search']['value'] != '')? sizeof($datas['data']):$datas['recordsTotal'];
            if (sizeof($datas['data']) <= 0) {
                $datas['data'] = array();
            }
        }

        return response()->json($datas);
    }

    public function event_export(Request $request)
    {
        $report = DB::select(
            DB::raw("SELECT coupon_data.cpId,
                    coupon_data.cpUserID,
                    coupon_data.cpLink,
                    coupon_data.cpCode,
                    coupon_data.cpType,
                    coupon_data.cpTakeTime,
                    coupon_data.cpIP,
                    coupon_data.cpStatus,
                    user_data.userName,
                    user_data.userOA
                    FROM coupon_data
                    LEFT JOIN user_data ON ( user_data.userID=coupon_data.cpUserID )
                    WHERE coupon_data.cpTake=1 AND coupon_data.cpUserID IS NOT NULL
                    ORDER BY coupon_data.cpTakeTime ASC
                    ")
        );

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('獎項領取總覽');
        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '序號')
            ->setCellValue('B1', '獎項')
            ->setCellValue('C1', '資格')
            ->setCellValue('D1', '領獎人LINE ID')
            ->setCellValue('E1', '領獎人姓名')
            ->setCellValue('F1', '領獎人LINE好友')
            ->setCellValue('G1', '領獎時間')
            ->setCellValue('H1', '領獎IP')
            ->setCellValue('I1', '獎項LINK')
            ->setCellValue('J1', '獎項Code');
        if ($report) {
            $row = 2;
            $no = 1;
            foreach ($report as $k => $v) {
                $prizeName = ($v->cpType == 'A')? '木瓜':'西瓜';
                $prizeStatus = ($v->cpStatus == 'N')? '正常':'好友';
                $prizeFriends = ($v->userOA == '1')? '好友':'無';

                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $v->cpId)
                ->setCellValue('B' . $row, $prizeName)
                ->setCellValue('C' . $row, $prizeStatus)
                ->setCellValue('D' . $row, $v->cpUserID)
                ->setCellValue('E' . $row, $v->userName)
                ->setCellValue('F' . $row, $prizeFriends)
                ->setCellValue('G' . $row, $v->cpTakeTime)
                ->setCellValue('H' . $row, $v->cpIP)
                ->setCellValue('I' . $row, $v->cpLink)
                ->setCellValue('J' . $row, $v->cpCode);

                $row += 1;
                $no += 1;
            }
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="水果牛奶活動_report_'.time().'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function setting(Request $request)
    {
        $setting = array();
        $dataAry = DB::table('app_config')->get();
        if ($dataAry) {
            foreach ($dataAry as $k => $v) {
                $setting[$v->app_key] = $v->app_val;
            }
        }


        $startDate = new \DateTime('2021-07-28');
        $endDate = new \DateTime('2021-08-13');

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($startDate, $interval, $endDate);

        foreach ($period as $date) {
            $d = $date->format("nd");
            $dKey = 'DAILY'.$d.'_MAX_MILK';
            $setting['DAILY'][$dKey]['date'] = $date->format("n/d");
            $setting['DAILY'][$dKey]['key'] = $dKey;
            $setting['DAILY'][$dKey]['val'] = $this->appconfig[$dKey];
            $setting['DAILY'][$dKey]['pass'] = (strtotime($date->format("Y-m-d")) < time())? 1:0;
        }


        //全部抽獎數
        $report['ALL_TAKE_MILK'] = DB::table('coupon_data')
                                        ->where(function ($query) {
                                            $query->whereRaw("cpTake=1");
                                        })->count();
        $report['ALL_TAKE_PROGRESSBAR'] = round(($report['ALL_TAKE_MILK']/$setting['MAX_MILK'])*100);

        //當日總數
        $report['DAILY_MAX_MILK'] = $this->appconfig['DAILY_MAX_MILK'];
        $report['DAILY_MILK_TAKE'] = DB::table('coupon_data')
                                        ->where(function ($query) {
                                            $query->whereRaw("cpTake=1")
                                                ->whereRaw("DATE_FORMAT(cpTakeTime,'%Y-%m-%d') = '".date('Y-m-d')."'");
                                        })->count();
        $report['progressbar'] =  round(($report['DAILY_MILK_TAKE']/$report['DAILY_MAX_MILK'])*100);



        return view('admin.setting_home', [
            'menu' => 'dashboard',
            'breadcrumb' => array('主控台', '活動設定'),
            'quicklink' => array( 'title'=> '水果牛奶', 'link' => route('admin.dashboard') ),
            'setting' => $setting,
            'report' => $report,
        ]);
    }

    public function setting_update(Request $request)
    {
        foreach ($request->input("setting") as $k => $v) {
            $app_key = $k;
            $sql['app_val'] = $v;

            DB::table('app_config')->updateOrInsert(
                ['app_key'=>$app_key],
                $sql
            );
        }
        return redirect()
        ->intended(route('admin.setting'))
        ->with('status', '活動設定儲存完成');
    }
}
