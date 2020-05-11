@extends('hrms.master')
@section('title','Details')
@section('content')
    <app-division-details
        :division-id="{{$id}}"
        return-uri="/hrms/{{(!empty($scope) && $scope=='executive-secretary') ? $scope : 'divisions'}}"
        title="@yield('title')"
        scope="{{$scope}}">
    </app-division-details>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
