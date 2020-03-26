@extends('layouts.smarthr.maroon.master')
@section('title','Users')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2>@yield('title')</h2>
        </div>
        <div class="col-sm-4">
            {!! link_to_route('users.create','New User',[],['class'=>'btn btn-primary btn-sm pull-right']) !!}
        </div>
    </div>
    <div class="row m-b-5">
        <div class="col table-responsive">
            <table class="table table-striped custom-table mb-0 datatable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Position</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <h2 class="table-avatar">
                                <a href="#" class="avatar"><img alt="" src="{{asset('images/avatar.png')}}"></a>
                                <a href="#">{{$user->fullName()}} <span>Web Developer</span></a>
                            </h2>
                        </td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone}}</td>
                        <td>@if($role = $user->roles()->first()){{$role->name}}@endif</td>
                        <td>Position</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <th colspan="6">No data</th>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

    </script>
@endsection
