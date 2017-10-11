@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1
                id="title"
                data-payment-processor-slug="{{$paymentProcessor->slug}}"
            >{!! link_to_route('payment-processors.show', $paymentProcessor->name, $paymentProcessor->slug) !!} Faucets</h1>
        </div>
        <div class="row" style="margin:0 0 0 0;">
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                    {!! Form::button(
                        '<i class="fa fa-2x fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New Faucet',
                        [
                            'type' => 'button',
                            'onClick' => "location.href='" . route('faucets.create') . "'",
                            'class' => 'btn btn-primary btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                            'style' => 'margin:0.25em 0.25em 0.25em 0; color: white; min-width:16em;'
                        ])
                    !!}
                    @if($paymentProcessor->isDeleted())
                        {!! Form::open(['route' => ['payment-processors.restore', $paymentProcessor->slug], 'method' => 'patch', 'class' => 'form-inline']) !!}
                        {!! Form::button(
                            '<i class="fa fa-2x fa-refresh" style="vertical-align: middle; margin-right:0.25em;"></i>Restore Payment Processor',
                            [
                                'type' => 'submit',
                                'class' => 'btn btn-info col-lg-2 col-md-2 col-sm-3 col-xs-12',
                                'style' => 'margin:0 0 0.25em 0; color: white; min-width:16em;',
                                'onclick' => "return confirm('Are you sure you want to restore this archived payment processor?')"
                            ])
                        !!}
                    @endif
                @endif
            @endif
        </div>
    </section>
    <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
        <div class="clearfix"></div>
        @include('flash::message')
        @if(!empty($message))
            <div class="alert alert-info">
                {!! $message !!}
            </div>
        @endif
        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                @include('layouts.partials.advertising.ads')
                @if(count($faucets) > 0)
                    @include('payment_processors.faucets.table')
                @else
                    <p>The payment processor has been temporarily deleted. Any associated faucets will show again once this payment processor has been restored.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('google-analytics')
    @include('layouts.partials.tracking._google_analytics')
@endsection