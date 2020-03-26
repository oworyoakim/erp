@extends('layouts.smarthr.maroon.master')
@section('title','General')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2>@yield('title')</h2>
        </div>
    </div>

    <div class="row m-b-5">
        <div class="col">
            {!! Form::open(array('url' => route('settings.general'), 'method' => 'post', 'class'=>"form-horizontal","enctype"=>"multipart/form-data")) !!}
            {{method_field('PUT')}}
            <div class="form-group row">
                {!! Form::label('company_name',trans('general.company_name'),array('class'=>'control-label col-md-3')) !!}
                <div class="col-md-9">
                    {!! Form::text('company_name',settings()->get('company_name'),array('class'=>'form-control form-focus','required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('company_email',trans('general.company_email'),array('class'=>'control-label col-md-3')) !!}
                <div class="col-md-9">
                    {!! Form::email('company_email',settings()->get('company_email'),array('class'=>'form-control')) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('company_phone',trans_choice('general.phone',2),array('class'=>'control-label col-md-3')) !!}
                <div class="col-md-9">
                    {!! Form::text('company_phone',settings()->get('company_phone'),array('class'=>'form-control')) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('company_website',trans('general.company_website'),array('class'=>'control-label col-md-3')) !!}
                <div class="col-md-9">
                    {!! Form::text('company_website',settings()->get('company_website'),array('class'=>'form-control')) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('company_address',trans('general.company_address'),array('class'=>'control-label col-md-3')) !!}
                <div class="col-md-9">
                    {!! Form::textarea('company_address',settings()->get('company_address'),array('class'=>'form-control','rows'=>3)) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('company_logo',trans('general.company_logo'),array('class'=>'control-label col-md-3')) !!}
                <div class="col-md-9">
                    @if(!empty(settings()->get('company_logo')))
                        <img src="{{ settings()->get('company_logo') }}" width="50" height="50"
                             class="img-responsive"/>
                    @endif
                    {!! Form::file('company_logo',array('class'=>'form-control')) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('system_version',trans('general.system_version'),array('class'=>'control-label col-md-3')) !!}
                <div class="col-md-9">
                    {!! Form::text('system_version',settings()->get('system_version'),array('class'=>'form-control')) !!}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-info pull-right">{{ trans('general.save') }}</button>
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
