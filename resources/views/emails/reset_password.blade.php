@extends('emails.email_layout')
@section('title',"{{$subject}}")
@section('content')
<p class="h2">Hello, {{$fullName}}</p>
<p>We have received a  request to reset password for the {{$companyName}} ERP account registered with this email address.</p>
<p>Please complete your request by clicking on the button below or ignore if this request was not initiated by you.</p>

<p>
    <a href="{{$resetPasswordLink}}"
       target="_blank"
       style="font-size: 16px;
       color: #fff;
       padding: 10px 20px;
       border-radius: 3px;
       display: inline-block;
       text-decoration: none;
       background-color: #33adff;">
        Reset Your Account Password
    </a>
</p>

<p><b>Important:</b> If your request is not completed within 24 hours, it will be deleted automatically.</p>
<p>Warm Regards</p>
@endsection

