@php
    $approved_request_counter = \App\Http\Helpers\FirefighterHelper::approved_request_counter();
    $failed_request_counter = \App\Http\Helpers\FirefighterHelper::failed_request_counter();
    $rejected_request_counter = \App\Http\Helpers\FirefighterHelper::rejected_request_counter();
    $awarded_request_counter  = \App\Http\Helpers\FirefighterHelper::awarded_request_counter();
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="width: 251px;">
    <a href="{{ route('firefighters.dashboard') }}" class="brand-link pl-3"><img width="150" style="filter: invert(1);" src="{{ \App\Http\Helpers\Helper::get_logo_link() }}"></a>
    <!-- Sidebar -->
    <div class="sidebar">
        @include('partials.firefighter-user-panel-sidebar',['user'=>$user,'UserHelper'=>$UserHelper])
        <nav id="sidebar" class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('firefighters.dashboard') }}" class="nav-link {{ strpos($route,'dashboard') !== false ? 'active' : '' }}">
                        <span class="material-icons">dashboard</span>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('firefighters.profile') }}" class="nav-link {{ strpos($route,'firefighters.profile') !== false ? 'active' : '' }}">
                        <span class="material-icons">perm_identity</span>
                        <p>
                            Personal Information
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ (strpos($route,'firefighters.semester.') !== false || strpos($route,'firefighters.my-courses.') === 0 || strpos($route,'firefighters.classes.index') !== false) || strpos($route,'credit-type.') !== false ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link">
                        <span class="material-icons">school</span>
                        <p>
                            Training Details
                            <i class="material-icons right">keyboard_arrow_left</i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($route,'firefighters.semester.') !== false ? 'active' : '' }}" href="{{ route('firefighters.semester.index') }}">
                                    <span class="material-icons">panorama_fish_eye</span>
                                    Semesters
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($route,'firefighters.my-courses.') !== false || strpos($route,'firefighters.classes.index') !== false ? 'active' : '' }}" href="{{ route('firefighters.my-courses.index') }}">
                                    <span class="material-icons">panorama_fish_eye</span>
                                    My Courses
                                </a>
                            </li>
                    </ul>
                </li>



                <li class="nav-item">
                    <a class="nav-link {{ strpos($route,'firefighters.all.certification.index') !== false || strpos($route,'firefighters.all.certification.show') !== false ? 'active' : '' }}" href="{{ route('firefighters.all.certification.index') }}">
                        <span class="material-icons">stars</span>
                        All Credentials
                    </a>
                </li>
                <li class="nav-item has-treeview {{ strpos($route,'firefighters.apply-certificates.index') !== false || strpos($route,'firefighters.apply-certificates.show') !== false || strpos($route,'firefighters.reject-certificates.index') !== false || strpos($route,'firefighters.reject-certificates.show') !== false || strpos($route,'firefighters.approved-certificates.index') !== false || strpos($route,'firefighters.approved-certificates.show') !== false || strpos($route,'firefighters.failed-certificates.index') !== false  || strpos($route,'firefighters.failed-certificates.show') !== false ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link">
                        <span class="material-icons">stars</span>
                        <p>
                            My Credentials
                            <i class="material-icons right">keyboard_arrow_left</i>

                            @if($approved_request_counter->certificate_update > 0 || $rejected_request_counter->certificate_update > 0 || $failed_request_counter->certificate_update)
                                <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                            @endif
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($route,'firefighters.apply-certificates.index') !== false || strpos($route,'firefighters.apply-certificates.show') !== false  ? 'active' : '' }}" href="{{ route('firefighters.apply-certificates.index') }}">
                                <span class="material-icons">panorama_fish_eye</span>
                                Applied Credentials
                            </a>
                        </li>

                        <li class="nav-item">

                            <a class="nav-link {{ strpos($route,'firefighters.reject-certificates.index') !== false || strpos($route,'firefighters.reject-certificates.show') !== false ? 'active' : '' }}" href="{{ route('firefighters.reject-certificates.index') }}">
                                <span class="material-icons">panorama_fish_eye</span>
                                Rejected Credentials
                                @if($rejected_request_counter->certificate_update > 0)
                                    <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ strpos($route,'firefighters.approved-certificates.index') !== false || strpos($route,'firefighters.approved-certificates.show') !== false  ? 'active' : '' }}" href="{{ route('firefighters.approved-certificates.index') }}">
                                <span class="material-icons">panorama_fish_eye</span>
                                Approved Credentials

                                @if($approved_request_counter->certificate_update  > 0)
                                    <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($route,'firefighters.failed-certificates.index') !== false || strpos($route,'firefighters.failed-certificates.show') !== false  ? 'active' : '' }}" href="{{ route('firefighters.failed-certificates.index') }}">
                                <span class="material-icons">panorama_fish_eye</span>
                                Failed Credentials
                                @if($failed_request_counter->certificate_update > 0)
                                    <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ strpos($route,'firefighters.awarded-certificates.index') !== false || strpos($route,'firefighters.certifications-past-records') !== false  ? 'active' : '' }}" href="{{ route('firefighters.awarded-certificates.index') }}">
                        <span class="material-icons">stars</span>
                        Awarded Credentials
                        @if($awarded_request_counter->certificate_update > 0)
                            <span class="material-icons" style=" margin-left:6px; margin-top:7px; font-size:8px; color:red;">stop_circle</span>
                        @endif
                    </a>
                </li>



                <!-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('firefighters.certicates.history') }}">
                        <span class="material-icons">stars</span>
                         Credentials History
                       
                    </a>
                </li> -->





            </ul>
        </nav>
    </div>
</aside>
