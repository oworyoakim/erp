<div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul>
            {{--            Dashboard    --}}
            <li class="@if(Request::is('dashboard*')) active @endif">
                <a href="{{route('hrms.dashboard')}}"><i class="la la-dashboard"></i><span>Dashboard</span>
                </a>
            </li>

            {{--            Coorporate Structure     --}}
            <li class="submenu @if(Request::is('executive-secretary*') || Request::is('directorates*') || Request::is('departments*') || Request::is('divisions*') || Request::is('sections*')) active @endif">
                <a href="#"><i class="la la-tasks"></i> <span> Coorporate Structure</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('executive-secretary*') || Request::is('directorates*') || Request::is('departments*') || Request::is('divisions*') || Request::is('sections*')) block  @else none @endif;">
                    <li class="@if(Request::is('executive-secretary*')) active @endif"><a
                            href="{{route('hrms.executive-secretary')}}"><span>Executive Secretary</span></a></li>
                    <li class="@if(Request::is('directorates*')) active @endif"><a
                            href="{{route('hrms.directorates.list')}}"><span>Manage Directorates</span></a></li>
                    <li class="@if(Request::is('departments*')) active @endif"><a
                            href="{{route('hrms.departments.list')}}"><span>Manage Departments</span></a></li>
                    <li class="@if(Request::is('divisions*')) active @endif"><a
                            href="{{route('hrms.divisions.list')}}"><span>Manage Divisions</span></a></li>
                    <li class="@if(Request::is('sections*')) active @endif"><a href="{{route('hrms.sections.list')}}"><span>Manage Sections</span></a>
                    </li>
                </ul>
            </li>

            {{--            Designations     --}}
            <li class="submenu @if(Request::is('designations*')) active @endif">
                <a href="#"><i class="la la-tasks"></i> <span> Designations</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('designations*')) block  @else none @endif;">
                    <li><a href="{{route('hrms.designations.list')}}">Manage Designations</a></li>
                    <li><a href="{{route('hrms.salary-scales.list')}}">Manage Salary Scales</a></li>
                </ul>
            </li>

            {{--            Employees     --}}
            <li class="@if(Request::is('employees*')) active @endif">
                <a href="{{route('hrms.employees.list')}}"><i
                        class="la la-users"></i><span>Manage Employees</span>
                </a>
            </li>
            {{--            Leaves     --}}
            <li class="submenu @if(Request::is('leaves*')) active @endif">
                <a href="#"><i class="la la-leaf"></i> <span> Leaves</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('leaves*')) block  @else none @endif;">
                    <li><a href="{{route('hrms.leaves.list')}}">All Leaves</a></li>
                    <li><a href="{{route('hrms.leaves.applications')}}">Leave Applications</a></li>
                </ul>
            </li>

            {{--            Document Management     --}}
            <li class="@if(Request::is('documents*')) active @endif">
                <a href="{{route('hrms.documents')}}"><i
                        class="la la-book"></i><span>Manage Documents</span>
                </a>
            </li>

            @include('common_menu')
        </ul>
    </div>
</div>
