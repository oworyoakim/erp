<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Enterprise Resource Planner">
    <meta name="keywords" content="Access Control, ERP, HRMS, SPMS">
    <meta name="author" content="Owor Yoakim">
    <meta name="robots" content="noindex, nofollow">
    <title>Login - Service</title>
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
<div class="main-wrapper">
    <div class="account-content">
        <div class="container">
            <div class="account-box">
                <div class="account-wrapper">
                    <h3 class="account-title">Select Service</h3>
                    <!-- Service Form -->
                    {!! Form::open(['url'=>route('service'),'method'=>'post']) !!}
                        <div class="form-group">
                            <select name="service" class="form-control" required>
                                <option value="">Select....</option>
                                <option value="hrms">HRMS</option>
                                <option value="spms">SPMS</option>
                                <option value="acl">ACL</option>
                            </select>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary account-btn" type="submit">Go</button>
                        </div>
                    {!! Form::close() !!}
                    <!-- /Service Form -->
                    <!--  Logout Form  -->
                    <div class="m-t-15">
                        {!! Form::open(['url'=>route('logout'),'method'=>'post']) !!}
                            <div class="form-group text-center">
                                <button class="btn btn-link" type="submit">Logout</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!--  /Logout Form  -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Main Wrapper -->
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

</body>
</html>
