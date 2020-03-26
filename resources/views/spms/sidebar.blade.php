<div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul>
            {{--            Dashboard    --}}
            <li class="@if(Request::is('dashboard*')) active @endif">
                <a href="{{route('spms.dashboard')}}"><i class="la la-dashboard"></i><span>Dashboard</span>
                </a>
            </li>

            {{--            Plans    --}}
            <li class="@if(Request::is('dashboard*')) active @endif">
                <a href="{{route('spms.plans')}}"><i class="la la-paw"></i><span>Strategic Plans</span>
                </a>
            </li>

            @include('common_menu')
        </ul>
    </div>
</div>
