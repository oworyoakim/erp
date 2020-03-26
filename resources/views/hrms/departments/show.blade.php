@extends('hrms.master')
@section('title','Details')
@section('content')
    <app-department-details :return-uri="'{{(isset($scope) && $scope=='executive-secretary') ? '/'.$scope : '/departments'}}'"
                            :title="'@yield('title')'" :department="{{$department}}"
                            :scope="'{{$scope}}'">
    </app-department-details>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
