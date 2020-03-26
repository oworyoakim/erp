
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Error 404 - HRMS</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('smarthr/maroon/img/favicon.png')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('smarthr/maroon/css/font-awesome.min.css')}}">

    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="{{asset('smarthr/maroon/css/line-awesome.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('smarthr/maroon/css/style.css')}}">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{asset('smarthr/maroon/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('smarthr/maroon/js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body class="error-page">
<!-- Main Wrapper -->
<div class="main-wrapper">

    <div class="error-box">
        <h1>403</h1>
        <h3><i class="fa fa-warning"></i> Oops! Forbidden Request!</h3>
        <p>The page you requested was rejected by the server.</p>
        <a href="{{route('dashboard')}}" class="btn btn-custom">Back to Home</a>
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

</body>
</html>
