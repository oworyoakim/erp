@extends('hrms.employee')
@section('title','Employee Profile')
@section('content')
    <app-employee-profile
        :username="'{{$username}}'"
        :title="'@yield('title')'">
    </app-employee-profile>
@endsection
