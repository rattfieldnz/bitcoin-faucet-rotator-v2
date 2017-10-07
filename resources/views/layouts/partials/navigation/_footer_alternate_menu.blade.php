<ul id="footer-alternate-menu">
    <li class="{{ Request::is('/') ? 'active' : '' }}">
        <a href="/" title="Home">Home</a>
    </li>
    <li class="{{ Request::is('faucets*') ? 'active' : '' }}">
        <a href="{!! route('faucets.index') !!}" title="Faucets">Faucets</a>
    </li>

    <li class="{{ Request::is('payment-processors*') ? 'active' : '' }}">
        <a href="{!! route('payment-processors.index') !!}" title="Payment Processors">Payment Processors</a>
    </li>

    <li class="{{ Request::is('users*') ? 'active' : '' }}">
        <a href="{!! route('users.index') !!}" title="Users">Users</a>
    </li>

@if(Auth::user() != null)
    @if(Auth::user()->is_admin == true)
        <li class="{{ Request::is('main-meta*') ? 'active' : '' }}">
            <a href="{!! route('main-meta.index') !!}" title="Main Meta">Main Meta</a>
        </li>

        <li class="{{ Request::is('ad-block*') ? 'active' : '' }}">
            <a href="{!! route('ad-block.index') !!}" title="Ad Block">Ad Block</a>
        </li>

        <li class="{{ Request::is('twitter-config*') ? 'active' : '' }}">
            <a href="{!! route('twitter-config.index') !!}" title="Twitter Config">Twitter Config</a>
        </li>

        <li class="{{ Request::is('roles*') ? 'active' : '' }}">
            <a href="{!! route('roles.index') !!}" title="Roles">Roles</a>
        </li>

        <li class="{{ Request::is('permissions*') ? 'active' : '' }}">
            <a href="{!! route('permissions.index') !!}">Permissions</a>
        </li>
    @endif
@endif

    <li class="{{ Request::is('privacy-policy*') ? 'active' : '' }}">
        <strong><a href="{!! route('privacy-policy') !!}" title="Privacy Policy">Privacy Policy</a></strong>
    </li>

    <li class="{{ Request::is('terms-and-conditions*') ? 'active' : '' }}">
        <strong><a href="{!! route('terms-and-conditions') !!}" title="Terms and Conditions">Terms &amp; Conditions</a></strong>
    </li>
</ul>