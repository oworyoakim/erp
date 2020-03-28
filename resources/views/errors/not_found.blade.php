@extends("{$service}.master")
@section('title','Not Found')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-12">
            <div class="error-box">
                <h1>@yield('title')</h1>
                <h3>The page you are looking for was not found in this server.</h3>
            </div>
        </div>
    </div>
@endsection
