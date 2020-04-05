@extends('spms.master')
@section('title',"Strategic Objective Details")
@section('content')
    <app-strategic-objective-details :objective-id="'{{$objectiveId}}'" start-of-next-financial-year="'{{$startOfNextFinancialYear}}'"></app-strategic-objective-details>
@endsection
