<div id="guest-content-wrapper">
    @include('layouts.partials.navigation._guest_navigation')
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