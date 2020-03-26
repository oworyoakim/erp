@extends('hrms.master')
@section('title','Download Profile')
@section('content')
    <app-download-employee-profile :title="'@yield('title')'"></app-download-employee-profile>
@endsection
