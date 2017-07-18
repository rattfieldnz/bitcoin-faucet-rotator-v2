@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{!! $paymentProcessor->name !!} Payment Processor</h1>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <p class="pull-right">
                    <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px;margin-left:10px;" href="{!! route('payment-processors.create') !!}">Add New</a>
                    <a class="btn btn-default pull-right" style="margin-top: -10px;margin-bottom: 5px;margin-left:10px;" href="{!! route('payment-processors.edit', ['slug' => $paymentProcessor->slug]) !!}">Edit</a>
                    @if($paymentProcessor->isDeleted())
                        {!! Form::open(['route' => ['payment-processors.delete-permanently', $paymentProcessor->slug], 'method' => 'delete', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Permanently Delete', ['type' => 'submit', 'class' => 'btn btn-danger', 'style' => 'margin-top:-10px;margin-bottom: 5px;', 'onclick' => "return confirm('Are you sure? The payment processor will be PERMANENTLY deleted!')"]) !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['payment-processors.restore', $paymentProcessor->slug], 'method' => 'patch', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Restore', ['type' => 'submit', 'class' => 'btn btn-info', 'style' => 'margin-top: -10px;margin-bottom:5px;margin-right:10px;', 'onclick' => "return confirm('Are you sure you want to restore this archived payment processor?')"]) !!}
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['route' => ['payment-processors.destroy', 'slug' => $paymentProcessor->slug], 'method' => 'delete', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Archive/Delete', ['type' => 'submit', 'class' => 'btn btn-warning', 'style' => 'margin-top: -10px;margin-bottom: 5px;', 'onclick' => "return confirm('Are you sure you want to archive this payment processor?')"]) !!}
                        {!! Form::close() !!}
                    @endif
                </p>
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
                <div class="row" style="padding-left: 20px">
                    @include('payment_processors.show_fields')
                    <a href="{!! route('payment-processors.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('google-analytics')
    @include('partials.google_analytics')
@endsection