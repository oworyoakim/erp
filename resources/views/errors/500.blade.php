@extends("errors.elayout")
@section('title','500 - Server Error')
@section('content')
    <h1>500</h1>
    <h3><i class="fa fa-warning"></i> Oops! Something went wrong</h3>
    <p>The server encountered an error while processing your request.</p>
    @if(!empty($error))
        <p>{{$error}}</p>
    @endif
@endsection
