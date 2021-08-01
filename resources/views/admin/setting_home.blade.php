@extends('admin.layouts.global')

@section('custom_css')
<link href="assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="plugins/bootstrap-select/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="plugins/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">
<link rel="stylesheet" type="text/css" href="plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
<link href="plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
<link href="plugins/noUiSlider/custom-nouiSlider.css" rel="stylesheet" type="text/css">
<link href="plugins/bootstrap-range-Slider/bootstrap-slider.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/kindeditor/themes/default/default.css">
<link rel="stylesheet" type="text/css" href="plugins/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="assets/css/dashboard/dash_2.css"  />
@endsection

@section('custom_js')
<script src="assets/js/scrollspyNav.js"></script>
<script src="plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="plugins/bootstrap-touchspin/custom-bootstrap-touchspin.js"></script>
<script src="plugins/flatpickr/flatpickr.js"></script>
<script src="plugins/noUiSlider/nouislider.min.js"></script>
<script src="plugins/select2/select2.min.js"></script>
<script src="plugins/kindeditor/kindeditor-all-min.js"></script>
<script src="plugins/kindeditor/lang/zh-TW.js"></script>
<script src="plugins/editors/quill/quill.js"></script>
<script>
    var _token = '{{csrf_token()}}';


    $(document).ready(function() {
        @if( isset($setting['HomeMainFeature']) )
        $("#formGroupFieldsMainFeature").val( '{!! preg_replace('/\r\n/', '\r\n', $setting['HomeMainFeature'])  !!}' );
        @endif

        $("a.btn-save").click(function(event){
            $("#submitfrm").submit();
        });
        @if (session('status'))
        fnNotifications('{{ session('status') }}');
        @endif

    });
</script>

@endsection



@section('content_html')
    @parent


        <form method="post" enctype="multipart/form-data" action="{{ route('admin.setting.update') }}" id="submitfrm">
            @csrf
            <div class="row layout-spacing layout-top-spacing">


                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info">
                                    <h6 class="value"> {!! $report['ALL_TAKE_MILK'] !!}/{!! $setting['MAX_MILK'] !!} </h6>
                                    <p class="">已發送比例</p>
                                </div>
                                <div class="">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: {!! $report['ALL_TAKE_PROGRESSBAR'] !!}%" aria-valuenow="{!! $report['ALL_TAKE_PROGRESSBAR'] !!}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info">
                                    <h6 class="value"> {!! $report['DAILY_MILK_TAKE'] !!}/{!! $report['DAILY_MAX_MILK'] !!} </h6>
                                    <p class="">當日已發送/當日總數</p>
                                </div>
                                <div class="">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: {!! $report['progressbar'] !!}%" aria-valuenow="{!! $report['progressbar'] !!}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-sm-12 ">
                    <div class="col-lg-12 layout-spacing layout-top-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>活動相關設定</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="form-group mb-2">
                                    <label for="formGroupFieldsWeeklyFeature">預計每日可抽獎總數</label>

                                    <input name="setting[DAILY_MAX_MILK]" class="form-control" id="formGroupFieldsDAILY_MAX_MILK" value="@if( isset($setting['DAILY_MAX_MILK'] ) ){!! $setting['DAILY_MAX_MILK'] !!}@endif">
                                </div>
                                <!--
                                <div class="form-group mb-2">
                                    <label for="formGroupFieldsColumnFeature">良品專欄</label>
                                    <textarea name="setting[Hometext][HomeColumnFeature]" class="form-control" id="formGroupFieldsColumnFeature">@if( isset($setting['HomeColumnFeature'] ) ){!! $setting['HomeColumnFeature'] !!}@endif </textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="formGroupFieldsLifeFeature">生活專欄</label>
                                    <textarea name="setting[Hometext][HomeLifeFeature]" class="form-control" id="formGroupFieldsLifeFeature">@if( isset($setting['HomeLifeFeature'] ) ){!! $setting['HomeLifeFeature'] !!}@endif </textarea>
                                </div>-->

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-12 col-lg-12 col-sm-12 ">
                    <div class="col-lg-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>活動每日最大贈品數量</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content widget-content-area">
                                <div class="form-row mb-4">
                                    @foreach ($setting['DAILY'] as $daily)

                                    <div class="form-group col-md-6">
                                        <label for="{!! $daily['key']!!}">{!! $daily['date']!!}</label>
                                        <input type="text" name="setting[{!! $daily['key']!!}]" class="form-control" id="{!! $daily['key']!!}" placeholder="" value="{!! $daily['val']!!}"   >
                                    </div>
                                        @if( $loop->iteration>1 && $loop->iteration%2 == 0 )
                                            </div><div class="form-row mb-4">
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-12 col-lg-12 col-sm-12 ">
                    <div class="col-lg-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <a class="btn btn-primary btn-save" href="javascript:void(0);">儲存</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </form>


@endsection
