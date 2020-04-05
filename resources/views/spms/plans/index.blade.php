@extends('spms.master')
@section('title','Strategic Plans')
@section('content')
    <app-plans start-of-next-financial-year="'{{$startOfNextFinancialYear}}'"></app-plans>
@endsection
