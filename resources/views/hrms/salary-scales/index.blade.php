@extends('hrms.master')
@section('title','Salary Scales')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2>@yield('title')</h2>
        </div>
        <div class="col-sm-4">
            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal" data-target="#salaryScaleModal"><i class="fa fa-plus"></i> New Scale</button>
        </div>
    </div>
    <app-salary-scales></app-salary-scales>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
