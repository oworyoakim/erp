@extends('hrms.master')
@section('title','Employees')
@section('content')
    <app-employees :title="'@yield('title')'"></app-employees>
@endsection
