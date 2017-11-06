<nav id="navbar-guest" class="navbar navbar-default" role="navigation">
    <div class="container" style="width: 100%;">
        <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Branding Image -->
            <p style="margin-top: -0.5em;">
                <a class="navbar-brand" href="{!! url('/') !!}" data-title="Bitcoin Faucet Rotator" title="Bitcoin Faucet Rotator">
                    <i class="fa fa-2x fa-bitcoin"></i>itcoin Faucet Rotator
                </a>
            </p>
        </div>
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul id="navigation" class="nav navbar-nav">
                <li class="standard-nav-item"><a href="/">Home</a></li>
                <li class="standard-nav-item">{!! link_to_route('faucets.index', "Faucets") !!}</li>
                <li class="standard-nav-item">{!! link_to_route('payment-processors.index', "Payment Processors") !!}</li>
                <li class="standard-nav-item">{!! link_to_route('users.index', "Users") !!}</li>
                <li class="standard-nav-item">{!! link_to_route('privacy-policy', "Privacy Policy") !!}</li>
                <li class="standard-nav-item">{!! link_to_route('terms-and-conditions', "Terms &amp; Conditions") !!}</li>
                <li class="standard-nav-item">{!! link_to_route('alerts.index', "Alerts") !!}</li>
                <li class="dropdown standard-nav-item">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Get Social! <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        @foreach(\App\Helpers\Social\SocialNetworkLinks::adminLinks() as $socialNetworkName => $link)
                            <li class="sub-nav-item">
                                <a
                                    href="{{ $link }}"
                                    title="{{ ucwords(str_replace('-', ' ', $socialNetworkName)) }}"
                                    target="_blank"
                                >
                                    <i class="fa fa-{{ $socialNetworkName }} fa-2x pull-left sub-nav-icon" aria-hidden="true"></i>
                                    <span class="pull-right sub-nav-text">{{ ucwords(str_replace('-', ' ', $socialNetworkName)) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="standard-nav-item"><a href="{!! url('/login') !!}">Login</a></li>
                <li class="standard-nav-item"><a href="{!! url('/register') !!}">Register</a></li>
            </ul>
        </div>
    </div>
</nav>