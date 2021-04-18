<div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul>
            {{--            Users     --}}
            @if(Sentinel::hasAnyAccess(['users','users.view','users.create','users.update','users.delete']))
                <li class="@if(Request::is('acl/users*')) active @endif">
                    <a href="{{route('users.list')}}"><i class="la la-user"></i><span>Manage Users</span>
                    </a>
                </li>
            @endif

            {{--            Roles     --}}
            @if(Sentinel::hasAnyAccess(['users.roles']))
                <li class="@if(Request::is('acl/roles*')) active @endif">
                    <a href="{{route('roles.list')}}"><i class="la la-group"></i><span>Manage Roles</span>
                    </a>
                </li>
            @endif

            {{--            Settings      --}}
            @if(Sentinel::hasAnyAccess(['settings']))
                <li class="submenu @if(Request::is('acl/settings*')) active @endif">
                    <a href="#"><i class="la la-gears"></i> <span> Settings</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: @if(Request::is('acl/settings*')) block  @else none @endif;">
                        <li><a href="{{route('settings.general')}}"> General Settings</a></li>
                        <li><a href="{{route('settings.modules')}}"> Module Access</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
