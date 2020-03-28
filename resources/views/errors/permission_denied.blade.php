@extends("{$service}.master")
@section('title','Permission Denied!')
@section('content')
    <div class="row m-b-5">
        <div class="col-sm-12">
            <div class="error-box">
                <h2>@yield('title')</h2>
                <h2>You are not authorized to view this resource.</h2>
            </div>
        </div>
    </div>
@endsection
