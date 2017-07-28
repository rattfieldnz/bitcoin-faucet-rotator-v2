<aside class="main-sidebar sidebar-fixed" id="sidebar-wrapper" style="position: fixed;">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="info">
                @if (Auth::guest())
                <p>InfyOm</p>
                @else
                    <figure>
                            <!--<img src="/assets/images/logos/blue_logo_150x150.jpg" class="img-circle"
                             alt="User Image"/>-->
                        <figcaption>
                            <div class="row" style="margin: 0 0 0 0;">
                                <p><strong>{{ Auth::user()->user_name}}</strong></p>
                                <p><i class="fa fa-circle text-success"></i> Online</p>
                                <p>Member since<br>{{ date('l jS \of F Y', strtotime(Auth::user()->created_at)) }}</p>
                            </div>
                            <div class="row" style="margin: 0 0 0 0;">
                                {!! link_to_route(
                                        'users.show',
                                        "Profile",
                                        ['slug' => Auth::user()->slug],
                                        ['class' => 'btn btn-primary col-xs-5', 'style' => 'margin:0 0.25em 0 1.75em;color:white !important;']
                                    )
                                !!}
                                <a href="{!! url('/logout') !!}" class="btn btn-primary col-xs-5" style="color:white !important;">Sign Out</a>
                            </div>
                        </figcaption>
                    </figure>
                @endif
            </div>
            <hr>
        </div>
        <!-- Sidebar Menu -->

        <ul class="sidebar-menu">
            @include('layouts.partials.navigation._menu')
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>