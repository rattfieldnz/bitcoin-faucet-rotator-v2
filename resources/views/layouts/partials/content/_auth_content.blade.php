<div>
    <!-- Main Header -->
    <header class="main-header" style="position:fixed; width:100%;">
        <!-- Logo -->
        <a href="#" class="logo">
            <b>InfyOm</b>
        </a>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" style="position:fixed; width:100%;" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="/assets/images/logos/blue_logo_150x150.jpg"
                                 class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="/assets/images/logos/blue_logo_150x150.jpg"
                                     class="img-circle" alt="User Image"/>
                                <p>
                                    {!! Auth::user()->user_name !!}
                                    <small>Member since {!! Auth::user()->created_at->format('M. Y') !!}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    {!! link_to_route(
                                            'users.show',
                                            "Profile",
                                            ['slug' => Auth::user()->slug],
                                            ['class' => 'btn btn-default btn-flat']
                                        )
                                    !!}
                                </div>
                                <div class="pull-right">
                                    <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
@include('layouts.partials.navigation._sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="padding-top:3em;">
        @yield('content')
    </div>
</div>