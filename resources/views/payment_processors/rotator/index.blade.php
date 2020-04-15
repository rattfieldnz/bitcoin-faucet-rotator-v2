@extends('layouts.app')

@section('content')
    <div style="margin:0 0 0 0;">
        <section class="content-header" style="margin-bottom: 1em;">
            <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
                <h1 id="title" style="text-align: center;" data-payment-processor-slug="{{ $paymentProcessor->slug }}">{{ $paymentProcessor->name }} Faucet Rotator</h1>
                @include('layouts.partials.social.addthis')
            </div>
        </section>
        @include('layouts.partials.site_wide_alerts._alert-content')
        <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">

            @include('layouts.partials.navigation._breadcrumbs')
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row zero-margin">
                        @include('layouts.partials.advertising.ads')
                        <div class="col-lg-12">
                            @include('rotator.partials.nav')
                        </div>
                        <div class="col-lg-12">
                            <iframe sandbox="allow-forms allow-scripts allow-pointer-lock allow-same-origin" src="" id="rotator-iframe"></iframe>
                            @include('layouts.partials.misc._no-iframe-faucet-content')
                            @include('layouts.partials.misc._ajax-data-error-content')
                        </div>
                    </div>
                    @include('layouts.partials.social.disqus')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset("/assets/js/rotator-scripts/paymentProcessorRotator.min.js?" . rand()) }}"></script>
@endpush

@push('google-analytics')
@include('layouts.partials.tracking._google_analytics')
@endpush

@push('jsonld_schema')

    <!-- START JSONld / schema -->
    @include('layouts.partials.seo._social_jsonld')
    <!-- END JSONld / schema -->

@endpush