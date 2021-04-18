@extends('hrms.employee')
@section('title','Employee Profile')
@section('content')
    <app-employee-profile-view
        :username="'{{$username}}'"
        :title="'@yield('title')'">
    </app-employee-profile-view>
@endsection
