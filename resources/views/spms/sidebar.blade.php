<div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul>
            {{--            Dashboard    --}}
            <li class="@if(Request::is('spms') || Request::is('spms/dashboard')) active @endif">
                <a href="{{route('spms.dashboard')}}"><i class="la la-dashboard"></i><span>DASHBOARD</span>
                </a>
            </li>

            {{--            Plan      --}}
            {{--            @if(Sentinel::hasAnyAccess(['plans.plan']))--}}
            <li class="@if(Request::is('spms/plans/plan*')) active @endif">
                <a href="{{route('spms.plans.plan')}}"><i class="la la-building"></i><span>PLAN STRATEGY</span>
                </a>
            </li>
            {{--  @endif--}}

            {{--            Execute      --}}
            {{--            @if(Sentinel::hasAnyAccess(['plans.execute']))--}}
            <li class="submenu @if(Request::is('spms/plans/execute*')) active @endif">
                <a href="#"><i class="la la-tasks"></i> <span> EXECUTE STRATEGY</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('spms/plans/execute*')) block  @else none @endif;">
                    <li><a href="{{route('spms.plans.execute.work-plan')}}">Work Plans and Activities</a></li>
                    <li><a href="{{route('spms.plans.execute.performance-capture')}}">Performance Capture</a></li>
                    <li><a href="{{route('spms.plans.execute.resolutions-and-directives')}}">Resolutions and Directives</a></li>
                </ul>
            </li>
            {{--            @endif--}}

            {{--            Monitor      --}}
            {{--            @if(Sentinel::hasAnyAccess(['plans.monitor']))--}}
            <li class="submenu @if(Request::is('spms/plans/monitor*')) active @endif">
                <a href="#"><i class="la la-bar-chart"></i> <span> MONITOR</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('spms/plans/monitor*')) block  @else none @endif;">
                    <li><a href="{{route('spms.plans.monitor.summary_strategy')}}">Summary Strategy Report</a></li>
                    <li><a href="{{route('spms.plans.monitor.detailed_strategy')}}">Detailed Strategy Report</a></li>
                    <li><a href="{{route('spms.plans.monitor.activity')}}">Activity</a></li>
                    <li><a href="{{route('spms.plans.monitor.directives-and-resolutions')}}">Directives and Resolution</a></li>
                </ul>
            </li>
            {{--            @endif--}}
        </ul>
    </div>
</div>
