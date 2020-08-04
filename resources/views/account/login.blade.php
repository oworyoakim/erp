@extends('account.layout')
@section('title','ACL Login')
@section('content')
    <div class="account-content">
        <div class="container">
            <!-- Account Logo -->
            <div class="account-logo">
                <a href="#"><img src="{{$companyLogo}}" alt="ERP"></a>
            </div>
            <!-- /Account Logo -->
            <app-login></app-login>
        </div>
    </div>

@endsection
@section('footer-scripts')
    <script>
        $(document).ready(function () {
            $('#login-form').show();
            $('#reset-form').hide();
        });

        function goToResetForm() {
            $('#login-form').hide();
            $('#reset-form').show();
        }

        function backToLoginForm() {
            $('#login-form').show();
            $('#reset-form').hide();
        }
    </script>
@endsection
