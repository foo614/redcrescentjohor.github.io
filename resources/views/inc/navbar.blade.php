<header class="mdl-layout__header mdl-color--grey-100">
    <div class="mdl-layout__header-row">
        <!-- Title -->
        <span class="mdl-layout-title">
        @if(Request::path() ==  'login' || Request::path() ==  'register')
            <div onclick="location='{{ url('/') }}'" style="cursor:pointer">
                <i class="material-icons moon-material-icon">brightness_3</i>
                <label class="mdl-color-text--black" style="font-weight: 500; font-size: 16px;">Red Crescent Johor</label>
            </div>
        @else 
            @yield('title')
        @endif
        </span>
        <!-- Add spacer, to align navigation to the right -->
        <div class="mdl-layout-spacer"></div>
        @if (Auth::check())
        <!-- Navigation -->
        <!-- Right aligned menu below button -->
        <md-menu md-size="medium" md-align-trigger>
            <md-avatar md-menu-trigger class="md-avatar-icon md-primary" id="menu-lower-right">
                <md-icon>people</md-icon>
            </md-avatar>
            <md-menu-content>
                <md-menu-item style="width: 280px;height: 72px; background-color:#eeeeee;">
                    <div style="display:flex;">
                        <md-avatar md-menu-trigger class="md-avatar-icon md-primary" id="menu-lower-right" style="margin-right: 16px;">
                            <md-icon>people</md-icon>
                        </md-avatar>
                        <div >
                            <label for="" style="display:block">{{Auth::user()->name}}</label>
                            <label for="">{{Auth::user()->email}}</label>
                        </div>
                    </div>
                </md-menu-item>
                <md-menu-item><md-icon>menu</md-icon>Menu</md-menu-item>
                <md-menu-item href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <md-icon>settings</md-icon>Sign out
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                </md-menu-item>
            </md-menu-content>
        </md-menu>
        @endif
    </div>
</header>
@if (Auth::check())
<div class="mdl-layout__drawer">
    <a href="/dashboard" class="mdl-button mdl-js-button mdl-js-ripple-effect sidebar-header md-inset" style="display: -webkit-inline-box; border-bottom: 1px solid #ccc;">
        <i class="material-icons moon-material-icon" style="transform: rotate(180deg); color:red;">brightness_3</i>
        <label class="mdl-color-text--black" style="font-weight: 500;">Red Crescent Johor</label>
    </a>
    <nav class="sidebar-nav">
        <ul class="metismenu" id="menu1">
            <li class="mdl-navigation__link">
                
                <a href="../" aria-expanded="false"><i class="material-icons m-r--24">computer</i>Website</a>
            </li>
            <li class="mdl-navigation__link">
                <a class="has-arrow" href="#" aria-expanded="false"><i class="material-icons m-r--24">supervisor_account</i>Member</a>
                <ul aria-expanded="false" class="collapse">
                    <li>
                        <a href="#">Add member</a>
                    </li>
                    <li>
                        <a href="#">Manage member</a>
                    </li>
                </ul>
            </li>
            <li class="mdl-navigation__link">
                <a class="has-arrow" href="#" aria-expanded="false"><i class="material-icons m-r--24">people</i>Donor</a>
                <ul aria-expanded="false" class="collapse">
                    <li>
                        <a href="#">Manage Donors</a>
                    </li>
                    <li>
                        <a href="#">Search Donors</a>
                    </li>
                </ul>
            </li>
            <li class="mdl-navigation__link">
                <a class="has-arrow" href="#" aria-expanded="false"><i class="material-icons m-r--24">local_hospital</i>Hospital</a>
                <ul aria-expanded="false" class="collapse">
                    <li>
                        <a href="#">Add Hospital</a>
                    </li>
                    <li>
                        <a href="#">Manage Hospitals</a>
                    </li>
                </ul>
            </li>
            <li class="mdl-navigation__link">
                <a class="has-arrow" href="#" aria-expanded="false"><i class="material-icons m-r--24">event_note</i>Post</a>
                <ul aria-expanded="false" class="collapse">
                    <li>
                        <a href="#">Add post</a>
                    </li>
                    <li>
                        <a href="#">Manage posts</a>
                    </li>
                </ul>
            </li>
            <li class="mdl-navigation__link">
                <a class="has-arrow" href="#" aria-expanded="false"><i class="material-icons m-r--24">settings</i>Setting</a>
                <ul aria-expanded="false" class="collapse">
                    <li>
                        <a href="#">Manage Permissions</a>
                    </li>
                    <li>
                        <a href="#">Manage Roles</a>
                    </li>
                    <li>
                        <a href="#">Manage Membership Type</a>
                    </li>
                    <li>
                        <a href="#">Manage Blood Type</a>
                    </li>
                    <li>
                        <a href="#">Manage Category Type</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
@endif