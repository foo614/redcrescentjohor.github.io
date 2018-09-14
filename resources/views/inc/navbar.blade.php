{{-- <header class="mdl-layout__header mdl-color--grey-100">
    <div class="mdl-layout__header-row">
        <!-- Title -->
        <span class="mdl-layout-title">
        @if(Request::path() ==  'login' || Request::path() ==  'register')
            <div onclick="location='{{ url('/') }}'" style="cursor:pointer">
                <i class="material-icons moon-material-icon">brightness_3</i>
                <label class="mdl-color-text--black" style="font-weight: 500; font-size: 16px;">Red Crescent Johor</label>
            </div>
        @else 
            <label class="mdl-color-text--black">@yield('title')</label>
        @endif
        </span>
        <!-- Add spacer, to align navigation to the right -->
        <div class="mdl-layout-spacer"></div>
        @if (Auth::check())
        <!-- Navigation -->
        <!-- Right aligned menu below button -->
        <button id="demo-menu-lower-right" class="mdl-button mdl-js-button mdl-button--icon" style="color:#000">
            <i class="material-icons">more_vert</i>
        </button>
        <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right demo-list-icon mdl-list" style="padding-top:0;" for="demo-menu-lower-right">
            <li class="mdl-menu__item mdl-list__item--two-line" style="width: 280px;height: 72px; background-color:#eeeeee;padding:10px;">
                <span class="mdl-list__item-primary-content">
                <v-avatar color="grey lighten-4" style="margin-right: 16px;" class="material-icons mdl-list__item-avatar"><img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar"></v-avatar>
                <span>{{Auth::user()->name}}</span>
                <span class="mdl-list__item-sub-title">{{Auth::user()->email}}</span>
            </li>
            <li class="mdl-menu__item mdl-list__item">
                <span class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon">account_box</i>Profile
                </span>
            </li>
            <li class="mdl-menu__item mdl-list__item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="logout" style="text-decoration: none; color: rgba(0,0,0,.87);">
                    <span class="mdl-list__item-primary-content">
                        <i class="material-icons mdl-list__item-icon">save_alt</i>Sign out
                    </span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
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
                        <a href="{{route('users.create')}}">Add member</a>
                    </li>
                    <li>
                        <a href="{{route('users.index')}}">Manage member</a>
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
                        <a href="#">Manage Roles</a>
                    </li>
                    <li>
                        <a href="#">Manage Membership Type</a>
                    </li>
                    <li>
                        <a href="/blood_types">Manage Blood Type</a>
                    </li>
                    <li>
                        <a href="#">Manage Category Type</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
@endif --}}

@auth
<navbar auth-name="{{ Auth::user()->name }}" auth-email="{{ Auth::user()->email }}" auth-avatar="{{ Auth::user()->avatar }}" data-login-status ="{{ Auth::check() }}"></navbar>
@endauth
{{-- <v-toolbar
color="transparent"
:clipped-left="$vuetify.breakpoint.mdAndUp"
app
>
<v-toolbar-title class="ml-0 pl-3">
<span>Red Crescent Johor</span>
</v-toolbar-title>
<v-spacer></v-spacer>
    @auth
    <!-- Right aligned menu below button -->
    <button id="drop-item" class="theme--dark">
        @if (Auth::user()->avatar != null)
            <v-avatar size="36">
                <img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="userAvatar">
            </v-avatar>
        @else
            <v-avatar color="teal lighten-2">
            <span class="white--text headline">{{substr(Auth::user()->name, 0, 1)}}</span>                
            </v-avatar>
        @endif
    </button>
    <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right demo-list-icon mdl-list" style="padding-top:0;" for="drop-item">
        <li class="mdl-menu__item mdl-list__item--two-line" style="width: 280px;height: 72px; background-color:#eeeeee;padding:10px;">
            <span class="mdl-list__item-primary-content">
            <v-avatar style="margin-right: 16px;" class="material-icons mdl-list__item-avatar"><img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar"></v-avatar>
            <span>{{Auth::user()->name}}</span>
            <span class="mdl-list__item-sub-title">{{Auth::user()->email}}</span>
        </li>
        <li class="mdl-menu__item mdl-list__item">
            <span class="mdl-list__item-primary-content">
                <i class="material-icons mdl-list__item-icon">account_box</i>Profile
            </span>
        </li>
        <li class="mdl-menu__item mdl-list__item">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="logout" style="text-decoration: none; color: rgba(0,0,0,.87);">
                <span class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon">save_alt</i>Sign out
                </span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
    @endauth
</v-toolbar> --}}
