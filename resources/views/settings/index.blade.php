@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row settings-heading">
            <h1 id="title">Settings</h1>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row profile-padding">
                    @include('layouts.partials.advertising.ads')
                    <div id="tabs">
                        <ul>
                            <li><h3><a href="#main-meta">Main Meta</a></h3></li>
                            <li><h3><a href="#ad-block">Ad Block</a></h3></li>
                            <li><h3><a href="#twitter-config">Twitter Config</a></h3></li>
                            <li><h3><a href="#roles">Roles</a></h3></li>
                            <li><h3><a href="#permissions">Permissions</a></h3></li>
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

                        </div>
                        <div id="permissions">

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