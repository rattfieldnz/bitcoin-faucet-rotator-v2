@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{!! link_to_route('payment-processors.show', $paymentProcessor->name, $paymentProcessor->slug) !!} Faucets</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                @if(count($faucets) > 0)
                    @include('payment_processors.faucets.table')
                @else
                    <p>There are currently no faucets that use this payment processor.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('google-analytics')
    @include('partials.google_analytics')
@endsection