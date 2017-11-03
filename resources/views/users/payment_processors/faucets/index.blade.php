@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1 id="title" data-user-slug="{{ $user->slug }}" data-payment-processor-slug="{{ $paymentProcessor->slug }}">
               {{ $user->user_name }}'s {!! link_to_route('payment-processors.show', $paymentProcessor->name, $paymentProcessor->slug, ["target" => "_blank"]) !!} Faucets
            </h1>
            @include('layouts.partials.social.addthis')
        </div>
    </section>
    <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                @include('layouts.partials.advertising.ads')
                @if(count($faucets) > 0)
                @include('users.payment_processors.faucets.table')
                @else
                    <p>That user has no faucets with this payment processor.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('google-analytics')
    @include('layouts.partials.tracking._google_analytics')
@endpush