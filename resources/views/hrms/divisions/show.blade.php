@extends('hrms.master')
@section('title','Details')
@section('content')
    <app-division-details :return-uri="'{{(isset($scope) && $scope=='executive-secretary') ? '/'.$scope : '/divisions'}}'"
                          :title="'@yield('title')'"
                          :division="{{$division}}"
                          :scope="'{{$scope}}'">
    </app-division-details>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
