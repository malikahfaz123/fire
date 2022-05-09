@php
    $admin_certificate_request_counter=\App\Http\Helpers\FirefighterHelper::admin_certificate_request_counter();
    $admin_course_request_counter=\App\Http\Helpers\FirefighterHelper::admin_course_request_counter();
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link pl-3">
        <img width="150" style="filter: invert(1);" src="{{ \App\Http\Helpers\Helper::get_logo_link() }}">
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        @include('partials.user-panel-sidebar',['user'=>$user,'UserHelper'=>$UserHelper])
        <nav id="sidebar" class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ strpos($route,'dashboard') !== false ? 'active' : '' }}">
                        <span class="material-icons">dashboard</span>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @if($user->can('firefighters.read'))
                <li class="nav-item">
                    <a class="nav-link {{ (strpos($route,'firefighter.')) !== false || (strpos($route,'completed-course.')) !== false ? 'active' : '' }}" href="{{ route('firefighter.index') }}">
                        <span class="material-icons">person</span>
                        <p>
                           Personnel Information
                        </p>
                    </a>
                </li>
                @endif

                @if($user->can('semesters.read') || $user->can('courses.read') || $user->can('courses.read') )
                    <li class="nav-item has-treeview {{ (strpos($route,'semester.') !== false || strpos($route,'course.') === 0 || strpos($route,'class.') !== false) || strpos($route,'credit-type.') !== false || strpos($route,'group-credit-type') !== false || strpos($route,'courses-credit-types.index') !== false  ? 'menu-open' : '' }}">
                        <a href="javascript:void(0)" class="nav-link">
                            <span class="material-icons">school</span>
                            <p>
                                Training Details
                                <i class="material-icons right">keyboard_arrow_left</i>
                                @if($admin_course_request_counter->course_request > 0)
                                    <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                @endif
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('semesters.read')
                                <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'semester.') !== false ? 'active' : '' }}" href="{{ route('semester.index') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                        Semesters
                                    </a>
                                </li>
                            @endcan
                            @can('courses.read')
                                <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'course.') !== false || strpos($route,'class.') !== false ? 'active' : '' }}" href="{{ route('course.index') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                        Courses
                                        @if($admin_course_request_counter->course_request > 0)
                                            <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                            @can('courses.read')
                                <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'credit-type.') !== false ? 'active' : '' }}" href="{{ route('credit-type.index') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                        Credit types
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'group-credit-types') !== false ? 'active' : '' }}" href="{{ route('group-credit-types.index') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                        Credit Types Groups
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'courses-credit-types.index') !== false ? 'active' : '' }}" href="{{ route('courses-credit-types.index') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                         Credit Types Courses
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if($user->can('certifications.read'))
                <li class="nav-item has-treeview {{ (strpos($route,'certification.')) !== false || (strpos($route,'certificate.create')) !== false ? 'menu-open' : '' }}" href="javascript:void(0);">
                    <a href="javascript:void(0);" class="nav-link">
                        <span class="material-icons">stars</span>
                        <p>
                            Credentials
                            <i class="material-icons right">keyboard_arrow_left</i>
                            @if($admin_certificate_request_counter->certificate_request  > 0)
                                <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                            @endif
                        </p>
                    </a>
                    <ul class="nav nav-treeview {{ (strpos($route,'certification.')) !== false || (strpos($route,'certificate.create')) !== false ? 'd-block' : '' }}">
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($route,'certification.create') !== false ? 'active' : '' }}" href="{{ route('certification.create') }}">
                                <span class="material-icons">add</span>
                                Add Credential
                            </a>
                        </li>
                        <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'certification.index') !== false ? 'active' : '' }}" href="{{ route('certification.index') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                        <p>Active</p>
                                        @if($admin_certificate_request_counter->certificate_request  > 0)
                                            <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                        @endif
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'certification.expired') !== false ? 'active' : '' }}" href="{{ route('certification.expired') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                        <p>Expired</p>
                                    </a>
                                </li>


<!-- 


                        <li class="nav-item menu-is-opening {{ strpos($route,'certification.index') !== false ? 'menu-open' : '' }}" style="cursor: pointer;">
                            <a class="nav-link">
                                <span class="material-icons">visibility</span>
                              
                                   View Credential
                                    <i class="material-icons right">keyboard_arrow_left</i>
                                    @if($admin_certificate_request_counter->certificate_request  > 0)
                                        <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                    @endif
                                
                            </a>
                            
                            <ul class="nav nav-treeview {{ (strpos($route,'certification.expired')) !== false || (strpos($route,'certificate.index')) !== false ? 'd-block' : '' }}">
                                <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'certification.index') !== false ? 'active' : '' }}" href="{{ route('certification.index') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                        <p>Active</p>
                                        @if($admin_certificate_request_counter->certificate_request  > 0)
                                            <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ strpos($route,'certification.expired') !== false ? 'active' : '' }}" href="{{ route('certification.expired') }}">
                                        <span class="material-icons">panorama_fish_eye</span>
                                        <p>Expired</p>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                    </ul>
                </li>
                @endif

                {{--@if($user->can('certifications.read'))
                    <li class="nav-item has-treeview {{ (strpos($route,'certification.')) !== false || (strpos($route,'certificate.create')) !== false ? 'menu-open' : '' }}" href="{{ route('certification.index') }}">
                        <a href="javascript:void(0)" class="nav-link">
                            <span class="material-icons">stars</span>
                            <p>
                                Credentials
                                <i class="material-icons right">keyboard_arrow_left</i>
                                @if($admin_certificate_request_counter->certificate_request  > 0)
                                    <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                @endif
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($route,'certification.create') !== false ? 'active' : '' }}" href="{{ route('certification.create') }}">
                                    <span class="material-icons">panorama_fish_eye</span>
                                    Add Credential
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($route,'certification.index') !== false ? 'active' : '' }}" href="{{ route('certification.index') }}">
                                    <span class="material-icons">panorama_fish_eye</span>
                                    View Credentials
                                </a>
                            </li>
                        </ul>
                        --}}{{--<a class="nav-link">
                            <span class="material-icons">stars</span>
                            Credentials
                            @if($admin_certificate_request_counter->certificate_request  > 0)
                                <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                            @endif
                        </a>--}}{{--
                    </li>
                @endif--}}

                @if($user->can('fire_departments.read'))
                    <li class="nav-item has-treeview {{ (strpos($route,'fire-department.')) !== false || (strpos($route,'fire-department-type.')) !== false ? 'menu-open' : '' }}" href="{{ route('fire-department.index') }}">
                        <a href="javascript:void(0)" class="nav-link">
                            <span class="material-icons">add_alert</span>
                            <p>
                                Fire Departments
                                <i class="material-icons right">keyboard_arrow_left</i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($route,'fire-department.') !== false ? 'active' : '' }}" href="{{ route('fire-department.index') }}">
                                    <span class="material-icons">panorama_fish_eye</span>
                                    Fire Departments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($route,'fire-department-type.') !== false ? 'active' : '' }}" href="{{ route('fire-department-type.index') }}">
                                    <span class="material-icons">panorama_fish_eye</span>
                                    Department types
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($user->can('organizations.read'))
                    <li class="nav-item">
                        <a class="nav-link {{ (strpos($route,'organization.')) !== false ? 'active' : '' }}" href="{{ route('organization.index') }}">
                            <span class="material-icons">business</span>
                            
                            <p>
                            Eligible Organizations
                        </p>
                        </a>
                    </li>
                @endif

                @if($user->can('facilities.read'))
                    <li class="nav-item has-treeview {{ strpos($route,'facility.') !== false || strpos($route,'facility-type.') !== false ? 'menu-open' : '' }}">
                        <a href="javascript:void(0)" class="nav-link">
                            <span class="material-icons">emoji_emotions</span>
                            <p>
                                Facilities
                                <i class="material-icons right">keyboard_arrow_left</i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($route,'facility.') !== false ? 'active' : '' }}" href="{{ route('facility.index') }}">
                                    <span class="material-icons">panorama_fish_eye</span>
                                    Facilities
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($route,'facility-type.') !== false ? 'active' : '' }}" href="{{ route('facility-type.index') }}">
                                    <span class="material-icons">panorama_fish_eye</span>
                                    Facility types
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($user->role->name == 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ (strpos($route,'reports.history')) !== false ? 'active' : '' }}" href="{{ route('reports.history') }}">
                            <span class="material-icons">subject</span>
                            <p>Data Logs</p>
                            
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
