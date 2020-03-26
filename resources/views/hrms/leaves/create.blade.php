@extends('hrms.master')
@section('title','New Leave')
@section('content')
    <div class="modal-content row">
        <div class="modal-header col-sm-12">
            <h2 class="modal-title">@yield('title')</h2>
            {!! link_to_route('leaves.list','Back To List',[],['class'=>'btn btn-secondary btn-sm pull-right']) !!}
        </div>
        <div class="modal-body col-sm-12">
            {!! Form::open(['url'=>route('leaves.create'),'method'=>'post']) !!}
            <div class="form-group row">
                <label class="col-sm-3">Leave Type <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! Form::select('leave_type_id',$leaveTypes,null,['class'=>"form-control select2",'placeholder'=>"Select...",'required'=>'required']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">From <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! Form::text('start_date',null,['class'=>'form-control datetimepicker']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">To <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! Form::text('end_date',null,['class'=>'form-control datetimepicker']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Leave Reason <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! Form::textarea('reason',null,['class'=>'form-control','rows'=>4]) !!}
                </div>
            </div>
            <div class="submit-section">
                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
