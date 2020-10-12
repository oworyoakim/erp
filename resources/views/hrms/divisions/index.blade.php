@extends('hrms.master')
@section('title','Divisions')
@section('content')
    <app-divisions :return-uri="'{{(isset($scope) && $scope=='executive-director') ? '/'.$scope : '/divisions'}}'"
                   :title="'@yield('title')'">
    </app-divisions>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
