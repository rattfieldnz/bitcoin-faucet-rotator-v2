<div style="background-color: #222d32;">
    <!-- Main Header -->
    <header class="main-header" style="position:fixed; width:100%;">
        <!-- Logo -->
        <a href="#" class="logo" style="font-size: 1em;">
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
    <div class="content-wrapper" style="padding-top:3em;">
        @yield('content')
    </div>
</div>