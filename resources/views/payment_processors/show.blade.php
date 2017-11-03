@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1>{{ $paymentProcessor->name }}</h1>
            @include('layouts.partials.social.addthis')
        </div>
        <div class="row" style="margin:0 0 0 0;">
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())

                    {!! Form::button(
                        '<i class="fa fa-2x fa-edit" style="vertical-align: middle; margin-right:0.25em;"></i>Edit Payment Processor',
                        [
                            'type' => 'button',
                            'onClick' => "location.href='" . route('payment-processors.edit', ['slug' => $paymentProcessor->slug]) . "'",
                            'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                            'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:16em;'
                        ])
                    !!}

                    {!! Form::button(
                        '<i class="fa fa-2x fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New Payment Processor',
                        [
                            'type' => 'button',
                            'onClick' => "location.href='" . route('payment-processors.create') . "'",
                            'class' => 'btn btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                            'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:16em;'
                        ])
                    !!}

                    @if($paymentProcessor->isDeleted())
                        {!! Form::open(['route' => ['payment-processors.delete-permanently', $paymentProcessor->slug], 'method' => 'delete', 'class' => 'form-inline']) !!}
                        {!! Form::button(
                            '<i class="fa fa-2x fa-trash" style="vertical-align: middle; margin-right:0.25em;"></i>Permanently Delete',
                            [
                                'type' => 'submit',
                                'class' => 'btn btn-danger col-lg-2 col-md-2 col-sm-3 col-xs-12',
                                'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:16em;',
                                'onclick' => "return confirm('Are you sure? The payment processor will be PERMANENTLY deleted!')"
                            ])
                        !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['payment-processors.restore', $paymentProcessor->slug], 'method' => 'patch', 'class' => 'form-inline']) !!}
                        {!! Form::button(
                            '<i class="fa fa-2x fa-refresh" style="vertical-align: middle; margin-right:0.25em;"></i>Restore',
                            [
                                'type' => 'submit',
                                'class' => 'btn btn-info col-lg-2 col-md-2 col-sm-3 col-xs-12',
                                'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:16em;',
                                'onclick' => "return confirm('Are you sure you want to restore this archived payment processor?')"
                            ])
                        !!}
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['route' => ['payment-processors.destroy', 'slug' => $paymentProcessor->slug], 'method' => 'delete', 'class' => 'form-inline']) !!}
                        {!! Form::button(
                            '<i class="fa fa-2x fa-trash" style="vertical-align: middle; margin-right:0.25em;"></i>Archive/Delete',
                            [
                                'type' => 'submit',
                                'class' => 'btn btn-warning col-lg-2 col-md-2 col-sm-3 col-xs-12',
                                'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:16em;',
                                'onclick' => "return confirm('Are you sure you want to archive this payment processor?')"
                            ])
                        !!}
                        {!! Form::close() !!}
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
                <div class="row zero-margin">
                    @include('layouts.partials.advertising.ads')
                    @include('payment_processors.show_fields')
                    <a href="{!! route('payment-processors.index') !!}" class="btn btn-default">Back</a>
                </div>
                @include('layouts.partials.social.disqus')
            </div>
        </div>
    </div>
@endsection

@push('google-analytics')
    @include('layouts.partials.tracking._google_analytics')
@endpush