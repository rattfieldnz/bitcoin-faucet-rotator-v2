<li class="{{ Request::is('faucets*') ? 'active' : '' }}">
    <a href="{!! route('faucets.index') !!}" title="Faucets"><i class="fa fa-bitcoin" aria-hidden="true"></i><span>Faucets</span></a>
</li>

<li class="{{ Request::is('payment-processors*') ? 'active' : '' }}">
    <a href="{!! route('payment-processors.index') !!}" title="Payment Processors"><i class="fa fa-bitcoin" aria-hidden="true"></i><span>Payment Processors</span></a>
</li>

<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}" title="Users"><i class="fa fa-users" aria-hidden="true"></i><span>Users</span></a>
</li>

@if(Auth::user() != null)
    <li class="{{ Request::is('stats*') ? 'active' : '' }}">
        <a href="{!! route('stats.index') !!}" title="Site Stats"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>Site Stats</span></a>
    </li>
    @if(Auth::user()->is_admin == true)
        <li class="{{ Request::is('settings*') ? 'active' : '' }}">
            <a href="{!! route('settings') !!}" title="Settings"><i class="fa fa-cog" aria-hidden="true"></i><span>Settings</span></a>
        </li>
    @endif
@endif

<li class="{{ Request::is('privacy-policy*') ? 'active' : '' }}">
    <a href="{!! route('privacy-policy') !!}" title="Privacy Policy"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><span>Privacy Policy</span></a>
</li>

<li class="{{ Request::is('terms-and-conditions*') ? 'active' : '' }}">
    <a href="{!! route('terms-and-conditions') !!}" title="Terms and Conditions"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><span>Terms &amp; Conditions</span></a>
</li>
