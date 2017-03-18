<li class="{{ Request::is('faucets*') ? 'active' : '' }}">
    <a href="{!! route('faucets.index') !!}"><i class="fa fa-edit"></i><span>Faucets</span></a>
</li>

<li class="{{ Request::is('payment-processors*') ? 'active' : '' }}">
    <a href="{!! route('payment-processors.index') !!}"><i class="fa fa-edit"></i><span>Payment Processors</span></a>
</li>

<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-edit"></i><span>Users</span></a>
</li>

@if(Auth::user() != null)
    @if(Auth::user()->is_admin == true)
        <li class="{{ Request::is('main-meta*') ? 'active' : '' }}">
            <a href="{!! route('main-meta.index') !!}"><i class="fa fa-edit"></i><span>Main Meta</span></a>
        </li>

        <li class="{{ Request::is('ad-block*') ? 'active' : '' }}">
            <a href="{!! route('ad-block.index') !!}"><i class="fa fa-edit"></i><span>Ad Blocks</span></a>
        </li>

        <li class="{{ Request::is('twitter-config*') ? 'active' : '' }}">
            <a href="{!! route('twitter-config.index') !!}"><i class="fa fa-edit"></i><span>Twitter Config</span></a>
        </li>
    @endif
@endif

