@extends('hrms.master')
@section('title','Profile')
@section('content')
    <app-employee-profile :username="'{{$username}}'" :title="'@yield('title')'"></app-employee-profile>
@endsection
