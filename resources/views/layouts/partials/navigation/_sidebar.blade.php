<aside class="main-sidebar sidebar-fixed" id="sidebar-wrapper">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="info">
                @if (Auth::guest())
                <p>Bitcoin Faucet Rotator</p>
                @else
                    <figure>
                        <div class="row">
                            <div class="col-xs-4">
                                <img src="{{ \Helpers\Functions\Users::getGravatar(Auth::user()) }}" class="img-circle" alt="User Image"/>
                            </div>
                            <figcaption class="col-xs-8">
                                <p id="username"><strong>{{ Auth::user()->user_name}}</strong></p>
                                <p id="status"><i class="fa fa-circle text-success"></i> Online</p>
                            </figcaption>

                        </div>
                        <div class="row" id="userdialog">
                            <p id="membersince">Member since<br>{{ date('l jS \of F Y', strtotime(Auth::user()->created_at)) }}</p>
                                {!! link_to_route(
                                        'users.show',
                                        "Profile",
                                        ['slug' => Auth::user()->slug],
                                        ['class' => 'btn btn-primary col-xs-5', 'style' => 'margin:0 0.25em 0 0em;color:white !important;']
                                    )
                                !!}
                                <a href="{!! url('/logout') !!}" class="btn btn-primary col-xs-5" style="color:white !important;">Sign Out</a>
                        </div>
                    </figure>
                @endif
            </div>
        </div>
        <!-- Sidebar Menu -->
        <h3 id="menuheader">Main Menu</h3>
        <ul class="sidebar-menu">
            @include('layouts.partials.navigation._menu')
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>