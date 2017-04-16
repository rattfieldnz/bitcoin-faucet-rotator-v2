@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Faucet - {!! $faucet->name !!}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @if($message != null)
            <div class="alert alert-info">
                {!! $message !!}
            </div>
        @endif
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <a href="{!! route('faucets.index') !!}" class="btn btn-default">Back</a>
                    @include('faucets.show_fields')
                    <a href="{!! route('faucets.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
