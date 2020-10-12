@extends('hrms.master')
@section('title','Details')
@section('content')
    <app-department-details
        :department-id="{{$id}}"
        :return-uri="'/hrms{{(isset($scope) && $scope=='executive-director') ? '/'.$scope : '/departments'}}'"
        scope="{{$scope}}"
        title="@yield('title')">
    </app-department-details>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
