@extends('layouts.smarthr.maroon.master')
@section('title','Global Settings')
@section('content')
    <app-breadcrumb :list-items="[
                    {href: '#', text: '@yield('title')', class: 'active'},
                ]"></app-breadcrumb>
    <div class="card card-body">
    {!! Form::open(array('url' => route('settings.general'), 'method' => 'put', "enctype"=>"multipart/form-data")) !!}
    <!-- Company Settings -->
        <div class="form-group row">
            <div class="col-md-6">
                {!! Form::label('company_name',trans('general.company_name'),array('class'=>'control-label')) !!}
                {!! Form::text('company_name',settings()->get('company_name'),array('class'=>'form-control form-focus','placeholder'=>'Example Company','required'=>'required')) !!}
            </div>
            <div class="col-md-6">
                {!! Form::label('company_email',trans('general.company_email'),array('class'=>'control-label')) !!}
                {!! Form::email('company_email',settings()->get('company_email'),array('class'=>'form-control','placeholder'=>'info@example.com')) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                {!! Form::label('company_phone',trans_choice('general.phone',2),array('class'=>'control-label')) !!}
                {!! Form::text('company_phone',settings()->get('company_phone'),array('class'=>'form-control','placeholder'=>'+256700000000')) !!}
            </div>
            <div class="col-md-6">
                {!! Form::label('company_website',trans('general.company_website'),array('class'=>'control-label')) !!}
                {!! Form::text('company_website',settings()->get('company_website'),array('class'=>'form-control','placeholder'=>'http://example.com/')) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                {!! Form::label('company_address',trans('general.company_address'),array('class'=>'control-label')) !!}
                {!! Form::text('company_address',settings()->get('company_address'),array('class'=>'form-control','placeholder'=> '3434 quiet valley lane, sherman oaks ca, 94344')) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                {!! Form::label('default_leave_application_verifier',trans('general.default_leave_application_verifier'),array('class'=>'control-label')) !!}
                {!! Form::select('default_leave_application_verifier',$designations,settings()->get('default_leave_application_verifier'),array('class'=>'form-control select2')) !!}
            </div>
            <div class="col-md-6">
                {!! Form::label('default_leave_application_granter',trans('general.default_leave_application_granter'),array('class'=>'control-label')) !!}
                {!! Form::select('default_leave_application_granter',$designations,settings()->get('default_leave_application_granter'),array('class'=>'form-control select2')) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                {!! Form::label('company_logo',trans('general.company_logo'),array('class'=>'control-label')) !!}
                @if(!empty(settings()->get('company_logo')))
                    <img src="{{ settings()->get('company_logo') }}" width="50" height="50"
                         class="img-responsive"/>
                @endif
                {!! Form::file('company_logo',array('class'=>'form-control')) !!}
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info pull-right">{{ trans('general.save') }}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
@endsection

