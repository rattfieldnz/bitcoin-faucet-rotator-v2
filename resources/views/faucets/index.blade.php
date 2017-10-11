@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1>Faucets</h1>
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
                            'style' => 'margin:0.25em 0 0 0; color: white; min-width:12em;'
                        ])
                    !!}
                @endif
            @endif
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
                @include('faucets.table')
            </div>
        </div>
    </div>
@endsection

@push('google-analytics')
    @include('layouts.partials.tracking._google_analytics')
@endpush