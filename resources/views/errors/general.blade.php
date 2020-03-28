@extends("{$service}.master")
@section('title','Ooops!')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-12">
            <div class="error-box">
                <h1>@yield('title')</h1>
                <h2>{{$error}}</h2>
            </div>
        </div>
    </div>
@endsection
