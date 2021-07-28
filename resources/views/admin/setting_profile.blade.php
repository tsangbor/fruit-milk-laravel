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
@endsection

@section('custom_js')
<script src="assets/js/scrollspyNav.js"></script>
<script src="plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="plugins/bootstrap-touchspin/custom-bootstrap-touchspin.js"></script>
<script src="plugins/flatpickr/flatpickr.js"></script>
<script src="plugins/noUiSlider/nouislider.min.js"></script>
<script src="plugins/select2/select2.min.js"></script>
<script>
    var _token = '{{csrf_token()}}';

    $(document).ready(function() {

        $("a.btn-save").click(function(event){
            $("#submitfrm").submit();
        });


        @if (session('status'))
        fnNotifications('{{ session('status') }}');
        @endif
        @if (session('error'))
        fnNotifications('{{ session('error') }}');
        @endif
    });
</script>

@endsection



@section('content_html')
    @parent

    <div id="content" class="main-content">
        <form method="post" enctype="multipart/form-data" action="{{ route('admin.setting.profile.update') }}" id="submitfrm">
            @csrf
        <div class="layout-px-spacing">
            <div class="row layout-spacing layout-top-spacing">

                <div class="col-xl-12 col-lg-12 col-sm-12 ">
                    <div class="col-lg-12 layout-spacing layout-top-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4> 帳戶資料 </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">

                                <div class="form-row mb-2">
                                    <div class="form-group col-md-6">
                                        <input value="@if( old('name') ){{ old('name') }}@else{{$userAry->name}}@endif" name="name" type="text" class="form-control" id="settingProfileName" placeholder="顯示名稱 *">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input value="{{$userAry->email}}" name="email" type="email" class="form-control" id="settingProfileEmail" placeholder="Email *">
                                    </div>

                                </div>
                                <div class="form-row mb-2">
                                    <div class="form-group col-md-6">
                                        <input name="password" type="password" class="form-control" id="settingProfilePassword" placeholder="變更密碼 *">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input name="password_confirmation" type="password" class="form-control" id="settingProfileConfirmPassword" placeholder="再次確認密碼 *">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="wedget-footer ">
                    <div class="col-xl-12 col-lg-12 col-sm-12 ">
                        <div class="col-lg-12">
                            <a class="btn btn-primary btn-save" href="javascript:void(0);">儲存</a>
                        </div>
                    </div>
                </div>




            </div>
        </div>
    </form>
    </div>

@endsection
