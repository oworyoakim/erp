<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Enterprise Resource Planner">
    <meta name="keywords" content="Access Control, ERP, HRMS, SPMS">
    <meta name="author" content="Owor Yoakim">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="base-url" content="{{ url('/') }}">
    <title>ERP - @yield('title')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <!-- Main CSS -->
    <link rel="stylesheet" href="/css/acl.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="{{asset('smarthr/maroon/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('smarthr/maroon/js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body class="error-page">
<!-- Main Wrapper -->
<div class="main-wrapper"  id="main-app">
    <div class="error-box">
        @yield('content')
        @if(!empty($service) && Sentinel::getUser()->canAccessModule($service))
        <a href="{{route($service.'.dashboard')}}" class="btn btn-custom">Go Back</a>
        @else
            <a href="{{route('hrms.dashboard')}}" class="btn btn-custom">Go Back</a>
        @endif
        <p class="mt-2">Or</p>
        <!--  Logout Form  -->
        {!! Form::open(array('url'=>route('logout'),'method'=>'post','class'=>'text-center')) !!}
            <button class="btn btn-link" type="submit">Logout</button>
        {!! Form::close() !!}
    <!--  /Logout Form  -->
    </div>
</div>
<!-- /Main Wrapper -->
<!-- Main JS -->
<script src="/js/acl.js"></script>
</body>
</html>
