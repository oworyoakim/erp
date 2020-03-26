@extends('hrms.master')
@section('title','New')
@section('content')
    <app-create-employee :title="'@yield('title')'" :next-id="'{{$nextId}}'"/>
@endsection
