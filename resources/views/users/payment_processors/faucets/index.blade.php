@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">
           {{ $user->user_name }}'s {!! link_to_route('payment-processors.show', $paymentProcessor->name, $paymentProcessor->slug, ["target" => "_blank"]) !!} Faucets
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
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