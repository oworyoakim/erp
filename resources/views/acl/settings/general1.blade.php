@extends('acl.master')
@section('title','Settings')
@section('content')
    <app-general-settings :title="'@yield('title')'"></app-general-settings>
@endsection
