<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Smarthr - Bootstrap Admin Template">
    <meta name="keywords"
          content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Login - HRMS admin template</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('smarthr/maroon/img/favicon.png')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('smarthr/maroon/css/bootstrap.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('smarthr/maroon/css/font-awesome.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('smarthr/maroon/css/style.css')}}">
    <!--    Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{asset('smarthr/maroon/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('smarthr/maroon/js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body class="account-page">

<!-- Main Wrapper -->
<div class="main-wrapper">
    <div class="account-content">
        <div class="container">
            <!-- Account Logo -->
            <div class="account-logo">
                <a href="#"><img src="{{asset('smarthr/maroon/img/logo2.png')}}" alt="Dreamguy's Technologies"></a>
            </div>
            <!-- /Account Logo -->

            <div class="account-box">
                <div class="account-wrapper">
                    <h3 class="account-title">Login</h3>
                    <p class="account-subtitle">Start your session</p>

                    <!-- Account Form -->
                    {!! Form::open(['url'=>route('login'),'method'=>'post']) !!}
                    <div class="form-group">
                        <label>Login Name</label>
                        <input class="form-control" type="text" name="login_name" placeholder="Enter login name" title="Please enter your login name" >
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" title="Please enter your password" placeholder="******" id="password">
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary account-btn" type="submit">Login</button>
                    </div>
                    <div class="account-footer">
                        <p>
                            Forgot password?
                            <a class="text-muted" href="#">
                                Reset here
                            </a>
                        </p>
                    </div>
                {!! Form::close() !!}
                <!-- /Account Form -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Main Wrapper -->

<!-- jQuery -->
<script src="{{asset('smarthr/maroon/js/jquery-3.2.1.min.js')}}"></script>

<!-- Bootstrap Core JS -->
<script src="{{asset('smarthr/maroon/js/popper.min.js')}}"></script>
<script src="{{asset('smarthr/maroon/js/bootstrap.min.js')}}"></script>

<!-- Custom JS -->
<script src="{{asset('smarthr/maroon/js/app.js')}}"></script>

<!--    Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
    @if(session()->has('success'))
    toastr.success('{{session('success')}}');
    @endif
    @if(session('warning'))
    toastr.warning('{{session('warning')}}');
    @endif
    @if(session('info'))
    toastr.info('{{session('info')}}');
    @endif
    @if(session('error'))
    toastr.error('{{session('error')}}');
    @endif
</script>

</body>
</html>
