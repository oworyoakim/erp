@extends('spms.master')
@section('title',"Key Result Areas Details")
@section('content')
    <app-key-result-area-details :key-result-area-id="'{{$keyResultAreaId}}'" start-of-next-financial-year="'{{$startOfNextFinancialYear}}'"></app-key-result-area-details>
@endsection
