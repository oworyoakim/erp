@extends('hrms.master')
@section('title','Leave Application Statuses')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2>@yield('title')</h2>
        </div>
        <div class="col-sm-4">
            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal" data-target="#leaveApplicationStatusModal"><i class="fa fa-plus"></i> Add Status</button>
        </div>
    </div>
    <app-leave-application-statuses></app-leave-application-statuses>
@endsection

@section('scripts')
    <script>
    </script>
@endsection
