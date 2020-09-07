<!-- Logo -->
<div class="header-left">
    <a href="{{route('users.list')}}" class="logo">
        @if($logo = settings()->get('company_logo'))
            <img src="{{$logo}}" width="40" height="40" alt="">
        @else
            <img src="{{asset('smarthr/maroon/img/logo.png')}}" width="40" height="40" alt="">
        @endif
    </a>
</div>
<!-- /Logo -->

<a id="toggle_btn" href="javascript:void(0);">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
</a>

<!-- Header Title -->
<div class="page-title-box">
    <h3>Access Control</h3>
</div>
<!-- /Header Title -->

<a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>
@if($user = Sentinel::check())
    <!-- Header Menu -->
    <ul class="nav user-menu">
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<span class="user-img"><img src="{{$user->avatar}}" alt="">
							<span class="status online"></span></span>
                <span>{{$user->username}}</span>
            </a>
            <ul class="dropdown-menu">
                <li class="dropdown-item"><a href="/acl/user/profile/{{$user->username}}">My Profile</a></li>
                <li class="dropdown-item"><a href="{{route('settings.general')}}">Settings</a></li>
                <a class="dropdown-item" href="{{route('service.change')}}">Exit ACL</a>
                <li class="dropdown-item">
                    <form method="post" action="{{route('logout')}}" id="sign-out-form" style="visibility: hidden">
                        {{csrf_field()}}
                    </form>
                    <a href="#" onclick="document.getElementById('sign-out-form').submit()">Logout</a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#">My Profile</a>
            <a class="dropdown-item" href="{{route('settings.general')}}">Settings</a>
            <a class="dropdown-item" href="#" onclick="document.getElementById('sign-out-form').submit()">Logout</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
@endif
