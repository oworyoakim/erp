@extends("errors.elayout")
@section('title','401 - Permission Denied!')
@section('content')
    <h1>401</h1>
    <h3><i class="fa fa-warning"></i> Oops! Permission Denied!</h3>
    <p>You are not authorized to view this resource.</p>
    @if(!empty($error))
        <p>{{$error}}</p>
    @endif
@endsection
