<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Enterprise Resource Planner">
    <meta name="keywords" content="Access Control, ERP, HRMS, SPMS">
    <meta name="author" content="Owor Yoakim">
    <meta name="robots" content="noindex, nofollow">
    <title>ERP - ACL Login</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/smarthr/maroon/img/favicon.png">
    <!-- Main CSS -->
    <link rel="stylesheet" href="/css/acl.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="{{asset('smarthr/maroon/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('smarthr/maroon/js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body class="account-page">
<!-- Main Wrapper -->
<div class="main-wrapper" id="main-app">
    <div class="account-content">
        <div class="container">
            <!-- Account Logo -->
            <div class="account-logo">
                <a href="#"><img src="{{asset('smarthr/maroon/img/logo2.png')}}" alt="ERP"></a>
            </div>
            <!-- /Account Logo -->
            <div class="account-box">
                <div class="account-wrapper" id="login-form">
                    <h3 class="account-title">Login</h3>
                    <p class="account-subtitle">Start your session</p>
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{session('error')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true" class="fa fa-times"></span>
                            </button>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{session('success')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true" class="fa fa-times"></span>
                            </button>
                        </div>
                    @endif
                <!-- Account Form -->
                    {!! Form::open(array('url'=>route('login'),'method'=>'post')) !!}
                    <div class="form-group">
                        <label>Login Name</label>
                        {!! Form::text('login_name',null,array('class'=> 'form-control','placeholder' => 'Enter login name','title' => 'Please enter your login name','required'=>'')) !!}
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        {!! Form::password('password',array('class'=> 'form-control','placeholder' => '******','title' => 'Please enter your password','required'=>'')) !!}
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary account-btn" type="submit">Login</button>
                    </div>
                    <div class="account-footer">
                        <p>
                            <a href="javascript:void(0);" onclick="goToResetForm()">Reset Password?</a>
                        </p>
                    </div>
                {!! Form::close() !!}
                <!-- /Account Form -->

                </div>
                <div class="account-wrapper" id="reset-form">
                    <h3 class="account-title">Reset your password</h3>
                    <br/>
                <!-- Password Reset Form -->
                    {!! Form::open(array('url'=>route('reset-password'),'method'=>'post')) !!}
                    <div class="form-group">
                        <label>Email Address</label>
                        {!! Form::email('email',null,array('class'=> 'form-control','placeholder' => 'Enter your email address','title' => 'Please enter your email address','required'=>'')) !!}
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary account-btn" type="submit">Reset Password</button>
                    </div>
                    <div class="account-footer">
                        <p>
                            <a href="javascript:void(0);" onclick="backToLoginForm()">Back to Login?</a>
                        </p>
                    </div>
                {!! Form::close() !!}
                <!-- /Password Reset Form -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Main Wrapper -->
<!-- Main JS -->
<script src="/js/acl.js"></script>
<script>
    $(document).ready(function () {
        $('#login-form').show();
        $('#reset-form').hide();
    });

    function goToResetForm() {
        $('#login-form').hide();
        $('#reset-form').show();
    }

    function backToLoginForm() {
        $('#login-form').show();
        $('#reset-form').hide();
    }
</script>
</body>
</html>
