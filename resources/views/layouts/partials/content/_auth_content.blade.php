<div id="auth-content-bg">
    <!-- Main Header -->
    <header id="auth-header" class="main-header">
        <!-- Logo -->
        <a id="auth-content-logo" href="{!! url('/') !!}" class="logo">
            <strong>Bitcoin Faucet Rotator</strong>
        </a>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    @include('layouts.partials.navigation._sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>