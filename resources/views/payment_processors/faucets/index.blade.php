@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{!! link_to_route('payment-processors.show', $paymentProcessor->name, $paymentProcessor->slug) !!} Faucets</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('payment_processors.faucets.table')
            </div>
        </div>
    </div>
@endsection

