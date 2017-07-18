@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Payment Processors</h1>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <h1 class="pull-right">
                   <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('payment-processors.create') !!}">Add New</a>
                </h1>
            @endif
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                    @include('payment_processors.table')
            </div>
        </div>
    </div>
@endsection

@section('google-analytics')
    @include('partials.google_analytics')
@endsection