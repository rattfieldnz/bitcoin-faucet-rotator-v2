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
        <a href="{!! route('users.index') !!}" title="Current Users">Current Users</a>
    </li>

@if(Auth::user() != null)
    <li class="{{ Request::is('stats*') ? 'active' : '' }}">
        <a href="{!! route('stats.index') !!}" title="Site Stats">Site Stats</a>
    </li>
    @if(Auth::user()->is_admin == true)
        <li class="{{ Request::is('settings*') ? 'active' : '' }}">
            <a href="{!! route('settings') !!}" title="Settings">Settings</a>
        </li>
    @endif
@endif

    <li class="{{ Request::is('privacy-policy*') ? 'active' : '' }}">
        <strong><a href="{!! route('privacy-policy') !!}" title="Privacy Policy">Privacy Policy</a></strong>
    </li>

    <li class="{{ Request::is('terms-and-conditions*') ? 'active' : '' }}">
        <strong><a href="{!! route('terms-and-conditions') !!}" title="Terms and Conditions">Terms &amp; Conditions</a></strong>
    </li>

@if(Auth::guest())
    <li class="{{ Request::is('login*') ? 'active' : '' }}">
        <a href="{!! url('/login') !!}">Login</a>
    </li>
    <li class="{{ Request::is('register*') ? 'active' : '' }}">
        <a href="{!! url('/register') !!}">Register</a>
    </li>
@endif

</ul>
<ul id="social-links-alternate-menu">
    @foreach(\App\Helpers\Social\SocialNetworkLinks::adminLinks() as $socialNetworkName => $link)
        <li>
            <a
                    href="{{ $link }}"
                    title="{{ ucfirst($socialNetworkName) }}"
                    target="_blank"
            >
                <i class="fa fa-{{ $socialNetworkName }} fa-2x" aria-hidden="true"></i>
            </a>
        </li>
    @endforeach
</ul>