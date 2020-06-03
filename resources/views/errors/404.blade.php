@extends("errors.elayout")
@section('title','400 - Not Found')
@section('content')
    <h1>404</h1>
    <h3><i class="fa fa-warning"></i> Oops! Page not found!</h3>
    <p>The page you requested for was not found in this server.</p>
    @if(!empty($error))
        <p>{{$error}}</p>
    @endif
@endsection
