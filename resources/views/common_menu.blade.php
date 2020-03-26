{{--            Users     --}}
<li class="submenu @if(Request::is('users*') || Request::is('roles*')) active @endif">
    <a href="#"><i class="la la-user"></i> <span>Manage Users</span> <span
            class="menu-arrow"></span></a>
    <ul style="display: @if(Request::is('users*') || Request::is('roles*')) block  @else none @endif;">
        <li><a href="{{route('users.list')}}">All Users</a></li>
        @if(Sentinel::hasAnyAccess(['users.roles']))
            <li><a href="{{route('roles.list')}}">Manage Roles</a></li>
        @endif
    </ul>
</li>

{{--            Settings      --}}
<li class="submenu @if(Request::is('settings*')) active @endif">
    <a href="#"><i class="la la-gears"></i> <span> Settings</span> <span
            class="menu-arrow"></span></a>
    <ul style="display: @if(Request::is('settings*')) block  @else none @endif;">
        <li><a href="{{route('settings.general')}}"> General Settings</a></li>
        <li><a href="{{route('settings.leave')}}">Leave Settings</a></li>
        <li><a href="{{route('settings.approvals')}}">Approval Settings</a></li>
    </ul>
</li>
