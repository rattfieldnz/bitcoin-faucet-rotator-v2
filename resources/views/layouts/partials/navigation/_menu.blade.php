<li class="{{ Request::is('faucets*') ? 'active' : '' }}">
    <a href="{!! route('faucets.index') !!}"><i class="fa fa-bitcoin" aria-hidden="true"></i><span>Faucets</span></a>
</li>

<li class="{{ Request::is('payment-processors*') ? 'active' : '' }}">
    <a href="{!! route('payment-processors.index') !!}"><i class="fa fa-bitcoin" aria-hidden="true"></i><span>Payment Processors</span></a>
</li>

<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-users" aria-hidden="true"></i><span>Users</span></a>
</li>

@if(Auth::user() != null)
    <li class="{{ Request::is('stats*') ? 'active' : '' }}">
        <a href="{!! route('stats.index') !!}"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>Site Stats</span></a>
    </li>
    @if(Auth::user()->is_admin == true)
        <li class="{{ Request::is('main-meta*') ? 'active' : '' }}">
            <a href="{!! route('main-meta.index') !!}"><i class="fa fa-edit" aria-hidden="true"></i><span>Main Meta</span></a>
        </li>

        <li class="{{ Request::is('ad-block*') ? 'active' : '' }}">
            <a href="{!! route('ad-block.index') !!}"><i class="fa fa-edit" aria-hidden="true"></i><span>Ad Block</span></a>
        </li>

        <li class="{{ Request::is('twitter-config*') ? 'active' : '' }}">
            <a href="{!! route('twitter-config.index') !!}"><i class="fa fa-twitter" aria-hidden="true"></i><span>Twitter Config</span></a>
        </li>

        <li class="{{ Request::is('roles*') ? 'active' : '' }}">
            <a href="{!! route('roles.index') !!}"><i class="fa fa-user-plus" aria-hidden="true"></i><span>Roles</span></a>
        </li>

        <li class="{{ Request::is('permissions*') ? 'active' : '' }}">
            <a href="{!! route('permissions.index') !!}"><i class="fa fa-user-plus" aria-hidden="true"></i><span>Permissions</span></a>
        </li>
    @endif
@endif

<li class="{{ Request::is('privacy-policy*') ? 'active' : '' }}">
    <a href="{!! route('privacy-policy.index') !!}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><span>Privacy Policy</span></a>
</li>

<li class="{{ Request::is('terms-and-conditions*') ? 'active' : '' }}">
    <a href="{!! route('terms-and-conditions.index') !!}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><span>Terms &amp; Conditions</span></a>
</li>
