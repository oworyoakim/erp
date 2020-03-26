@extends('hrms.master')
@section('title','Departments')
@section('content')
    <app-departments :return-uri="'{{(isset($scope) && $scope=='executive-secretary') ? '/'.$scope : '/departments'}}'"
                     :title="'@yield('title')'">
    </app-departments>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
