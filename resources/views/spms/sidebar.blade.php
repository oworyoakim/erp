<div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul>
            {{--            Dashboard    --}}
            <li class="@if(Request::is('spms') || Request::is('spms/dashboard')) active @endif">
                <a href="{{route('spms.dashboard')}}"><i class="la la-dashboard"></i><span>Dashboard</span>
                </a>
            </li>

            {{--            Plans      --}}
            {{--            @if(Sentinel::hasAnyAccess(['plans*']))--}}
            <li class="submenu @if(Request::is('spms/plans*') || Request::is('spms/objectives*') || Request::is('spms/key-result-areas*')) active @endif">
                <a href="#"><i class="la la-paw"></i> <span> Strategic Plans</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('spms/plans*') || Request::is('spms/objectives*') || Request::is('spms/key-result-areas*')) block  @else none @endif;">
                    <li><a href="{{route('spms.plans.plan')}}"> Plan</a></li>
                    <li><a href="{{route('spms.plans.execute')}}">Execute</a></li>
                    <li><a href="{{route('spms.plans.monitor')}}">Monitor</a></li>
                </ul>
            </li>
            {{--            @endif--}}
        </ul>
    </div>
</div>
