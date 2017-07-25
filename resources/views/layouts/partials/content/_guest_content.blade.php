<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
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
                <a class="navbar-brand" href="{!! url('/') !!}">
                    <i class="fa fa-2x fa-bitcoin"></i>itcoin Faucet Rotator
                </a>
            </p>
        </div>
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li>{!!  link_to_route('faucets.index', "Faucets") !!}</li>
                <li>{!!  link_to_route('payment-processors.index', "Payment Processors") !!}</li>
                <li>{!!  link_to_route('users.index', "Current Users") !!}</li>
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
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