@extends('layouts.smarthr.maroon.master')
@section('title','Edit Role')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-8">
            <h2><i class="fa fa-edit"></i>  @yield('title')</h2>
        </div>
        <div class="col-sm-4">
            @if(Sentinel::hasAnyAccess(['users.roles']))
                {!! link_to_route('users.list','Back To List',[],['class'=>'btn btn-default btn-sm pull-right']) !!}
            @endif
        </div>
    </div>

    <div class="row m-b-5">
        <div class="col">
            {!! Form::open(array('url' => route('roles.update',['id'=>$role->id]),'method'=>'PUT')) !!}
            <div class="form-group row">
                <label class="col-sm-4">Name</label>
                <div class="col-sm-8">
                    {!! Form::text('name',$role->name,array('class'=>'form-control','required'=>'required')) !!}
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <label class="col-sm-4">Role Permissions</label>
                <div class="col-sm-8">
                    <table class="table table-stripped table-hover">
                        @foreach($data as $permission)
                            <tr>
                                <td>
                                    @if($permission->parent_id == 0)
                                        <strong>{{ $permission->name }}</strong>
                                    @else
                                        __ {{ $permission->name }}
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($permission->description))
                                        <i class="fa fa-info" data-toggle="tooltip"
                                           data-original-title="{{$permission->description}}"></i>
                                    @endif
                                </td>
                                <td>
                                    {!! Form::checkbox('permission[]',$permission->slug,array_key_exists($permission->slug,$role->permissions),array('class'=>'form-control pcheck','data-parent'=>$permission->parent_id,'id'=>$permission->id,'style'=>'height: 30px;width: 30px;')) !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit"
                            class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}}</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.pcheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '30%' // optional
            });

            $(".pcheck").on('ifChecked', function (e) {
                if ($(this).attr('data-parent') == 0) {
                    var id = $(this).attr('id');
                    $(":checkbox[data-parent=" + id + "]").iCheck('check', {
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '30%' // optional
                    });

                }
            });
            $(".pcheck").on('ifUnchecked', function (e) {
                if ($(this).attr('data-parent') == 0) {
                    var id = $(this).attr('id');
                    $(":checkbox[data-parent=" + id + "]").iCheck('uncheck', {
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '30%' // optional
                    });

                }
            });
        })
    </script>
@endsection
