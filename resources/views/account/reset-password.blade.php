@extends('account.layout')
@section('title',"ACL Reset Password")
@section('content')
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="account-content">
            <div class="container">
                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title">Reset Password</h3>
                        <br/>&nbsp;
                        <!-- Reset Password Form -->
                        {!! Form::open(['url'=>route('do-reset-password'),'method'=>'put']) !!}
                        <input type="hidden" name="code" value="{{$code}}">
                        <div class="form-group row">
                            <label class="col-sm-4">Email Address: </label>
                            <div class="col-sm-8">
                                <input type="email" name="email" class="form-control" value="{{$email}}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">New Password: </label>
                            <div class="col-sm-8">
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Confirm Password: </label>
                            <div class="col-sm-8">
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-primary account-btn" type="submit">Reset Password</button>
                        </div>
                    {!! Form::close() !!}
                    <!-- /Reset Password Form -->
                        <div class="m-t-15 text-center">
                            <a class="" href="{{route('login')}}">Return to login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
@endsection

