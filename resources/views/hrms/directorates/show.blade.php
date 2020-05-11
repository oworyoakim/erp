@extends('hrms.master')
@section('title','Directorate Details')
@section('content')
    <app-directorate-details
        directorate-id="{{$id}}"
        return-uri="/hrms/directorates"
        title="@yield('title')">
    </app-directorate-details>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
