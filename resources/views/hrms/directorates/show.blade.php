@extends('hrms.master')
@section('title','Directorate Details')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2>@yield('title')</h2>
        </div>
        <div class="col-sm-4">
            <a href="{{route('directorates.list')}}" class="btn btn-dark btn-sm pull-right"><i class="fa fa-backward"></i> Back</a>
        </div>
    </div>
    <app-directorate-details :directorate="{{$directorate}}"></app-directorate-details>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
