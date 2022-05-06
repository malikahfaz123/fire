<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link pl-3"><img width="150" style="filter: invert(1);" src="{{ \App\Http\Helpers\Helper::get_logo_link() }}"></a>
    <!-- Sidebar -->
    <div class="sidebar">
        @include('partials.user-panel-sidebar',['user'=>$user,'UserHelper'=>$UserHelper])
        <nav id="sidebar" class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview {{ strpos($route,'user.') !== false || strpos($route,'settings.index') !== false || strpos($route,'firefighter.setting.invite-firefighter') !== false || strpos($route,'firefighter.setting.index') !== false || strpos($route,'role.') !== false ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link">
                        <span class="material-icons">people</span>
                        <p>
                            Users
                            <i class="material-icons right">keyboard_arrow_left</i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($route,'user.') !== false || strpos($route,'settings.index') !== false ? 'active' : '' }}" href="{{ route('settings.index') }}">
                                <span class="material-icons">panorama_fish_eye</span>
                                All users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($route,'role.') !== false ? 'active' : '' }}" href="{{ route('role.index') }}">
                                <span class="material-icons">panorama_fish_eye</span>
                                Roles & Permissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($route,'firefighter.setting.index') !== false|| strpos($route,'firefighter.setting.invite-firefighter') !== false ? 'active' : '' }}" href="{{ route('firefighter.setting.index') }}">
                                <span class="material-icons">panorama_fish_eye</span>
                                Invite Users
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (strpos($route,'.enrollment-limit')) !== false ? 'active' : '' }}" href="{{ route('settings.enrollment-limit') }}">
                        <span class="material-icons">school</span>
                       <p> Enrollment Limits</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (strpos($route,'.other-settings')) !== false ? 'active' : '' }}" href="{{ route('settings.other-settings') }}">
                        <span class="material-icons">settings</span>
                      <p>  Other Settings <p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>