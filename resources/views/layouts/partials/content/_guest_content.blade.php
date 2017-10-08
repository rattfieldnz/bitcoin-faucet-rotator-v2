<div id="guest-content-wrapper">
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
                    <li><a href="/">Home</a></li>
                    <li>{!! link_to_route('faucets.index', "Faucets") !!}</li>
                    <li>{!! link_to_route('payment-processors.index', "Payment Processors") !!}</li>
                    <li>{!! link_to_route('users.index', "Current Users") !!}</li>
                    <li>{!! link_to_route('privacy-policy', "Privacy Policy") !!}</li>
                    <li>{!! link_to_route('terms-and-conditions', "Terms &amp; Conditions") !!}</li>
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                    <li><a href="{!! url('/register') !!}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>