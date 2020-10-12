<div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul>
            {{--            Dashboard    --}}
            <li class="@if(Request::is('hrms/dashboard*')) active @endif">
                <a href="{{route('hrms.dashboard')}}"><i class="la la-dashboard"></i><span>Dashboard</span>
                </a>
            </li>

            {{--            Coorporate Structure     --}}
            <li class="submenu @if(Request::is(['hrms/executive-director*','hrms/directorates*','hrms/departments*','hrms/divisions*','hrms/sections*'])) active @endif">
                <a href="#"><i class="la la-tasks"></i> <span> Coorporate Structure</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is(['hrms/executive-director*','hrms/directorates*','hrms/departments*','hrms/divisions*','hrms/sections*'])) block  @else none @endif;">
                    <li class="@if(Request::is('hrms/executive-director*')) active @endif"><a
                            href="{{route('hrms.executive-director')}}"><span>Executive Director</span></a></li>
                    <li class="@if(Request::is('hrms/directorates*')) active @endif"><a
                            href="{{route('hrms.directorates.list')}}"><span>Manage Directorates</span></a></li>
                    <li class="@if(Request::is('hrms/departments*')) active @endif"><a
                            href="{{route('hrms.departments.list')}}"><span>Manage Departments</span></a></li>
                    <li class="@if(Request::is('hrms/divisions*')) active @endif"><a
                            href="{{route('hrms.divisions.list')}}"><span>Manage Divisions</span></a></li>
                    <li class="@if(Request::is('hrms/sections*')) active @endif"><a href="{{route('hrms.sections.list')}}"><span>Manage Sections</span></a>
                    </li>
                </ul>
            </li>

            {{--            Designations     --}}
            <li class="submenu @if(Request::is('hrms/designations*')) active @endif">
                <a href="#"><i class="la la-tasks"></i> <span> Designations</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('hrms/designations*')) block  @else none @endif;">
                    <li><a href="{{route('hrms.designations.list')}}">Manage Designations</a></li>
                    <li><a href="{{route('hrms.salary-scales.list')}}">Manage Salary Scales</a></li>
                </ul>
            </li>

            {{--            Employees     --}}
            <li class="@if(Request::is('hrms/employees*')) active @endif">
                <a href="{{route('hrms.employees.list')}}"><i
                        class="la la-users"></i><span>Manage Employees</span>
                </a>
            </li>
            {{--            Leaves     --}}
            <li class="submenu @if(Request::is('hrms/leaves*')) active @endif">
                <a href="#"><i class="la la-leaf"></i> <span> Leaves</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('hrms/leaves*')) block  @else none @endif;">
                    <li><a href="{{route('hrms.leaves.list')}}">All Leaves</a></li>
                    <li><a href="{{route('hrms.leaves.applications')}}">Leave Applications</a></li>
                </ul>
            </li>

            {{--            Document Management     --}}
            <li class="@if(Request::is('hrms/documents*')) active @endif">
                <a href="{{route('hrms.documents')}}"><i
                        class="la la-book"></i><span>Manage Documents</span>
                </a>
            </li>

            {{--            Settings      --}}
            @if(Sentinel::hasAnyAccess(['settings']))
                <li class="submenu @if(Request::is('hrms/settings*')) active @endif">
                    <a href="#"><i class="la la-gears"></i> <span> Settings</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: @if(Request::is('hrms/settings*')) block  @else none @endif;">
                        <li><a href="{{route('hrms.settings.leave')}}">Leave Settings</a></li>
                        <li><a href="{{route('hrms.settings.approvals')}}">Approval Settings</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
