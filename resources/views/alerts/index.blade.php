@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1 id="title">Alerts</h1>
            @include('layouts.partials.social.addthis')
        </div>
        @if(!empty(Auth::user()) && Auth::user()->isAnAdmin())
        <div class="row zero-margin buttons-row">
            {!! Form::button(
                '<i class="fa fa-2x fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New Alert',
                [
                    'type' => 'button',
                    'onClick' => "location.href='" . route('alerts.create') . "'",
                    'class' => 'btn btn-success col-lg-2 col-md-2 col-sm-2 col-xs-12',
                    'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;'
                ])
            !!}
        </div>
        @endif
    </section>
    <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row zero-margin">
                    @include('layouts.partials.advertising.ads')
                    @if(count($alerts) == 0)
                        <p>There are no alerts at this time.</p>
                    @else
                        @include('alerts.table')
                    @endif
                    @include('layouts.partials.social.disqus')
                </div>
            </div>
        </div>
    </div>
@endsection

