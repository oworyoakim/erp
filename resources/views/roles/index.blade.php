@extends('layouts.smarthr.maroon.master')
@section('title','Roles & Permissions')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2>@yield('title')</h2>
        </div>
        <div class="col-sm-4">

        </div>
    </div>
    <app-users-roles></app-users-roles>

@endsection
