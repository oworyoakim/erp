<div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul class="sidebar-menu-container">
            {{--            Dashboard    --}}
            <li class="@if(Request::is('hrms/dashboard*')) active @endif">
                <a href="{{route('hrms.dashboard')}}"><i class="la la-dashboard"></i><span>Dashboard</span>
                </a>
            </li>

            {{--            Coorporate Structure     --}}
            <li class="submenu @if(Request::is(['hrms/executive-director*','hrms/directorates*','hrms/departments*','hrms/divisions*','hrms/sections*'])) active @endif">
                <a href="#"><i class="la la-tasks"></i> <span>CORPORATE</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is(['hrms/executive-director*','hrms/directorates*','hrms/departments*','hrms/divisions*','hrms/sections*'])) block  @else none @endif;">
                    <li class="@if(Request::is('hrms/executive-director*')) active @endif"><a
                            href="{{route('hrms.executive-director')}}"><span>Executive Director</span></a></li>
                    <li class="@if(Request::is('hrms/directorates*')) active @endif"><a
                            href="{{route('hrms.directorates.list')}}"><span>Directorates</span></a></li>
                    <li class="@if(Request::is('hrms/departments*')) active @endif"><a
                            href="{{route('hrms.departments.list')}}"><span>Departments</span></a></li>
                    @if(Sentinel::hasAnyAccess(['divisions','divisions.create','divisions.update','divisions.view','divisions.delete']))
                        <li class="@if(Request::is('hrms/divisions*')) active @endif"><a
                                href="{{route('hrms.divisions.list')}}"><span>Divisions</span></a></li>
                    @endif
                    @if(Sentinel::hasAnyAccess(['sections','sections.create','sections.update','sections.view','sections.delete']))
                        <li class="@if(Request::is('hrms/sections*')) active @endif"><a
                                href="{{route('hrms.sections.list')}}"><span>Sections</span></a>
                        </li>
                    @endif
                </ul>
            </li>

            @if(Sentinel::hasAnyAccess(['designations','designations.create','designations.update','designations.view','designations.delete','salary_scales','salary_scales.view','salary_scales.create','salary_scales.update','salary_scales.delete']))
                {{--            Designations     --}}
                <li class="submenu @if(Request::is('hrms/designations*')) active @endif">
                    <a href="#"><i class="la la-tasks"></i> <span> DESIGNATIONS</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: @if(Request::is('hrms/designations*')) block  @else none @endif;">
                        @if(Sentinel::hasAnyAccess(['designations','designations.create','designations.update','designations.view','designations.delete']))
                            <li><a href="{{route('hrms.designations.list')}}">Manage Designations</a></li>
                        @endif
                        @if(Sentinel::hasAnyAccess(['salary_scales','salary_scales.view','salary_scales.create','salary_scales.update','salary_scales.delete']))
                            <li><a href="{{route('hrms.salary-scales.list')}}">Manage Salary Scales</a></li>
                        @endif
                    </ul>
                </li>
            @endif
            @if(Sentinel::hasAnyAccess(['employees','employees.view','employees.create','employees.update','employees.delete']))
                {{--            Employees     --}}
                <li class="@if(Request::is('hrms/employees*')) active @endif">
                    <a href="{{route('hrms.employees.list')}}"><i
                            class="la la-users"></i><span>MANAGE EMPLOYEES</span>
                    </a>
                </li>
            @endif

            @if(Sentinel::hasAnyAccess(['leaves','leaves.recall','leaves.applications','leaves.applications.apply','leaves.applications.verify','leaves.applications.approve','leaves.applications.decline','leaves.applications.return','leaves.applications.grant','leaves.applications.reject']))
                {{--            Leaves     --}}
                <li class="submenu @if(Request::is('hrms/leaves*')) active @endif">
                    <a href="#"><i class="la la-leaf"></i> <span> LEAVE</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: @if(Request::is('hrms/leaves*')) block  @else none @endif;">
                        @if(Sentinel::hasAnyAccess(['leaves','leaves.recall']))
                            <li><a href="{{route('hrms.leaves.list')}}">All Leaves</a></li>
                        @endif
                        @if(Sentinel::hasAnyAccess(['leaves.applications','leaves.applications.apply','leaves.applications.verify','leaves.applications.approve','leaves.applications.decline','leaves.applications.return','leaves.applications.grant','leaves.applications.reject']))
                            <li><a href="{{route('hrms.leaves.applications')}}">Leave Applications</a></li>
                        @endif
                    </ul>
                </li>
            @endif
            {{--            Document Management     --}}
            <li class="@if(Request::is('hrms/documents*')) active @endif">
                <a href="{{route('hrms.documents')}}"><i
                        class="la la-book"></i><span>DOCUMENTS</span>
                </a>
            </li>

            {{--            Discipline and Reward     --}}
            <li class="submenu @if(Request::is('#')) active @endif">
                <a href="#"><i class="la la-leaf"></i> <span>EMPLOYMENT</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('#')) block  @else none @endif;">
                    <li><a href="{{route('hrms.leaves.list')}}">Promotion</a></li>
                    <li><a href="{{route('hrms.leaves.applications')}}">Secondment</a></li>
                    <li><a href="{{route('hrms.leaves.applications')}}">Retirement</a></li>
                    <li><a href="{{route('hrms.leaves.applications')}}">Resignation</a></li>
                    <li><a href="{{route('hrms.leaves.applications')}}">Demotion</a></li>
                    <li><a href="{{route('hrms.leaves.applications')}}">Suspension</a></li>
                    <li><a href="{{route('hrms.leaves.applications')}}">Dismissal</a></li>
                </ul>
            </li>

            {{--            Performance Management     --}}
            <li class="submenu @if(Request::is('hrms/performance*')) active @endif">
                <a href="#"><i class="la la-leaf"></i> <span> PERFORMANCE</span> <span
                        class="menu-arrow"></span></a>
                <ul style="display: @if(Request::is('hrms.employees.list')) block  @else none @endif;">
                    <li><a href="{{route('hrms.performance.outputs-and-targets')}}">Agreed Outputs & Targets</a></li>
                    <li><a href="{{route('hrms.performance.appraisals')}}">Appraisals</a></li>
                    <li><a href="{{route('hrms.employees.list')}}">Training Assessment</a></li>
                    <li><a href="{{route('hrms.employees.list')}}">Improvement Plan</a></li>
                </ul>
            </li>

            {{--            Settings      --}}
            @if(Sentinel::hasAnyAccess(['settings']))
                <li class="submenu @if(Request::is('hrms/settings*')) active @endif">
                    <a href="#"><i class="la la-gears"></i> <span> HRMIS SETTINGS</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: @if(Request::is('hrms/settings*')) block  @else none @endif;">
                        <li><a href="{{route('hrms.settings.leave')}}">Leave </a></li>
                        <li><a href="{{route('hrms.settings.approvals')}}">Approvals</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
