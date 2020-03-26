@extends('hrms.master')
@section('title','Leave Applications')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2>@yield('title')</h2>
        </div>
        <div class="col-sm-4">
            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal" data-target="#leaveApplicationModal"><i class="fa fa-plus"></i> New Application</button>
        </div>
    </div>
    <app-leave-applications></app-leave-applications>
@endsection

@section('scripts')
    <script>
    </script>
@endsection
