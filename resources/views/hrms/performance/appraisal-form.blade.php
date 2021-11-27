@extends('hrms.master')
@section('title','Performance Appraisal Form')
@section('content')
    <app-performance-appraisal-form :title="'@yield('title')'" :employee-id="{{$employeeId}}"></app-performance-appraisal-form>
@endsection
