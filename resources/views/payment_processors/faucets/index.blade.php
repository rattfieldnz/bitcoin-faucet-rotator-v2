@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="clearfix"></div>
        <h1 class="pull-left">'{!! $paymentProcessor->name !!}' Faucets</h1>
        <div class="clearfix"></div>
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

