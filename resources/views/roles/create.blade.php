@extends('layouts.smarthr.maroon.master')
@section('title','New Role')
@section('content')
    <div class="modal-content row">
        <div class="modal-header col-sm-12">
            <h2 class="modal-title">@yield('title')</h2>
            {!! link_to_route('roles.list','Back To List',[],['class'=>'btn btn-secondary btn-sm pull-right']) !!}
        </div>
        <div class="modal-body col-sm-12">
            {!! Form::open(array('url' => route('roles.create'),'class'=>'')) !!}
            <div class="form-group row">
                <label class="col-sm-2">Name</label>
                <div class="col-sm-10">
                    {!! Form::text('name',null,array('class'=>'form-control','required'=>'required')) !!}
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <label class="col-sm-2">Role Permissions</label>
                <div class="col-sm-10">
                    <table class="table table-stripped table-hover">
                        @foreach($data as $permission)
                            <tr>
                                <td>
                                    @if($permission->parent_id == 0)
                                        <strong>{{$permission->name}}</strong>
                                    @else
                                        __ {{$permission->name}}
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($permission->description))
                                        <i class="fa fa-info" data-toggle="tooltip"
                                           data-original-title="{{$permission->description}}"></i>
                                    @endif
                                </td>
                                <td>
                                    {!! Form::checkbox('permission[]',$permission->slug,null,array('class'=>'form-control pcheck','data-parent'=>$permission->parent_id,'id'=>$permission->id,'style'=>'height: 30px;width: 30px;')) !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="submit-section">
                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
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
        });
    </script>
@endsection

