<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title> @yield('title', '水果牛奶') | 後端管理系統登入 </title>
    <link rel="icon" type="image/png" href="" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/authentication/form-1.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">
    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" type="text/css" href="assets/css/elements/alert.css">
    <style>
        img {width:100%;}
        .btn-light { border-color: transparent; }
        .form-form .form-form-wrap form .field-wrapper svg {
            color: #696969;
        }
        .form-form {
            margin: 0 auto;
        }
    </style>
    <!--  END CUSTOM STYLE FILE  -->

</head>
<body class="form">





    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">

                    <div class="form-content text-center">

                        <h1 class="mb-3"></h1>
                        <h3 class="text-center mb-4"> <span style="font-weight:bold;">水果牛奶</span> 後台管理系統 </h3>

                        <form class="text-left" method="POST" action="{{ route($loginRoute) }}">
                            @csrf
                            <div class="form">

                                @if (session('error'))
                                <div class="widget-content widget-content-area">
                                    <div class="alert alert-arrow-right alert-icon-right alert-light-danger mb-4" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                        <strong>{{ session('error') }}</strong>
                                    </div>
                                </div>
                                @endif


                                <div id="email-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input id="email" name="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="登入帳號（email）" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif

                                </div>

                                <div id="password-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="登入密碼">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif

                                </div>
                                <!--
                                <div id="captcha-field" class="field-wrapper input mb-2">
                                    <div class="form-row mb-2">
                                        <div class=" col-md-8">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            <input id="captcha" name="captcha" type="text" class="form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}" placeholder="驗證碼" value="">
                                            @if ($errors->has('captcha'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('captcha') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class=" col-md-4">
                                            <img src="{{ captcha_src() }}" alt="點擊刷新" onclick="this.src='{{ url('captcha/default') }}?s='+Math.random()">
                                        </div>
                                    </div>
                                </div>-->

                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper toggle-pass">
                                        <p class="d-inline-block">顯示密碼</p>
                                        <label class="switch s-primary">
                                            <input type="checkbox" id="toggle-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-primary" value="">登入</button>
                                    </div>

                                </div>

                            </div>
                        </form>



                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/authentication/form-1.js"></script>

</body>
</html>
