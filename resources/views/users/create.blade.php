@extends('layouts.smarthr.maroon.master')
@section('title','New User')
@section('content')
    <div class="modal-content row">
        <div class="modal-header col-sm-12">
            <h2 class="modal-title">@yield('title')</h2>
            {!! link_to_route('users.list','Back To List',[],['class'=>'btn btn-secondary btn-sm pull-right']) !!}
        </div>
        <div class="modal-body col-sm-12">
            {!! Form::open(['url'=>route('users.create'),'method'=>'post']) !!}
            <div class="form-group row">
                <label class="col-sm-3">First Name</label>
                <div class="col-sm-9">
                    {!! Form::text('first_name',null,['class'=>"form-control",'placeholder'=>"Enter First Name",'required'=>'required']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Last Name</label>
                <div class="col-sm-9">
                    {!! Form::text('last_name',null,['class'=>"form-control",'placeholder'=>"Enter Last Name",'required'=>'required']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Email</label>
                <div class="col-sm-9">
                    {!! Form::email('email',null,['class'=>"form-control",'placeholder'=>"Enter Email Address"]) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Role</label>
                <div class="col-sm-9">
                    {!! Form::select('role_id',$roles,null,['class'=>"form-control",'placeholder'=>"Select...",'required'=>'required']) !!}
                </div>
            </div>
            <h3 class="bg-navy disabled color-palette">Login Information</h3>
            <div class="form-group row">
                <label class="col-sm-3">Username</label>
                <div class="col-sm-9">
                    {!! Form::text('username',null,['class'=>"form-control",'placeholder'=>"Enter Username",'required'=>'required']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Password</label>
                <div class="col-sm-9">
                    {!! Form::password('password',['class'=>"form-control",'placeholder'=>"Enter password",'required'=>'required']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Confirm Password</label>
                <div class="col-sm-9">
                    {!! Form::password('password_confirmation',['class'=>"form-control",'placeholder'=>"Confirm password",'required'=>'required']) !!}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <button type="submit"
                            class="btn btn-primary pull-right submit-btn">{{trans('general.save')}}</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('scripts')
    <script>

    </script>
@endsection
