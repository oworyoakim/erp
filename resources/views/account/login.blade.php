@extends('account.layout')
@section('title','ACL Login')
@section('content')
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
@endsection
@section('footer-scripts')
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
@endsection
