@extends('hrms.master')
@section('title','Leave Types')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2>@yield('title')</h2>
        </div>
        <div class="col-sm-4">
            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal" data-target="#leaveTypeModal"><i class="fa fa-plus"></i> Add Leave Type</button>
        </div>
    </div>
    <app-leave-types></app-leave-types>
@endsection

@section('scripts')
    <script>
    </script>
@endsection
