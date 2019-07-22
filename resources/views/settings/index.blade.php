@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row settings-heading">
            <h1 id="title">Settings</h1>
        </div>
    </section>
    <div class="content">

        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row profile-padding">
                    @include('layouts.partials.advertising.ads')
                    <div id="tabs">
                        <ul id="tabMenu">
                            <li><h3><a href="#main-meta">Main Meta</a></h3></li>
                            <li><h3><a href="#ad-block">Ad Block</a></h3></li>
                            <li><h3><a href="#twitter-config">Twitter Config</a></h3></li>
                            <li><h3><a href="#roles">Roles</a></h3></li>
                            <li><h3><a href="#permissions">Permissions</a></h3></li>
                            <li><h3><a href="#social-links">Social Links</a></h3></li>
                            @if(Auth::user()->isAnAdmin())
                            <li><h3><a href="#export-data">Export Data</a></h3></li>
                            @endif
                        </ul>
                        <div id="main-meta">
                            @include('settings.main-meta')
                        </div>
                        <div id="ad-block">
                            @include('settings.ad-block')
                        </div>
                        <div id="twitter-config">
                            @include('settings.twitter-config')
                        </div>
                        <div id="roles">
                            @include('settings.roles')
                        </div>
                        <div id="permissions">
                            @include('settings.permissions')
                        </div>
                        <div id="social-links">
                            @include('settings.social-links')
                        </div>
                        <div id="export-data">
                            @include('settings.export-data')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $( function() {
        $( "#tabs" ).tabs();
    } );
</script>
@endpush