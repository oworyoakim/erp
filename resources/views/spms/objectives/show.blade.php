@extends('spms.master')
@section('title',"Strategic Objective Details")
@section('content')
    <app-strategic-objective-details :objective-id="'{{$objectiveId}}'"></app-strategic-objective-details>
@endsection
