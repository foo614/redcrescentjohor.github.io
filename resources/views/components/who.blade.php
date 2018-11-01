@if(Auth::guard('web')->check())
    <p>
        You logged in as web guest
    </p>
@else
    <p>
        You logged out as web guest
    </p>
@endif
@if(Auth::guard('admin')->check())
    <p>
        You logged in as admin
    </p>
@else
    <p>
        You logged out as admin
    </p>
@endif
