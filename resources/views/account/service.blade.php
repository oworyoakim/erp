@extends('account.layout')
@section('title',"Service Selection")
@section('content')
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="account-content">
            <div class="container">
                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title">Select Service</h3>
                        <br/>&nbsp;
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
@endsection
