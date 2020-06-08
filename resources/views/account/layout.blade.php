<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Enterprise Resource Planner">
    <meta name="keywords" content="Access Control, ERP, HRMS, SPMS">
    <meta name="author" content="Owor Yoakim">
    <meta name="robots" content="noindex, nofollow">
    <title>ERP - @yield('title')</title>
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
@yield('content')
<!-- Main JS -->
<script src="/js/acl.js"></script>
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
@yield('footer-scripts')
</body>
</html>
