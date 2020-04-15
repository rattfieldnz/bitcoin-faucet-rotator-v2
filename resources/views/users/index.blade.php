@extends('layouts.app')

@section('content')
    <section class="content-header" style="margin-bottom: 1em;">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1>Users</h1>
            @include('layouts.partials.social.addthis')
        </div>

        @if(!empty(Auth::user()) && Auth::user()->isAnAdmin())
            <div class="row zero-margin buttons-row">
                {!! Form::button(
                    '<i class="fa fa-2x fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New User',
                    [
                        'type' => 'button',
                        'onClick' => "location.href='" . route('users.create') . "'",
                        'class' => 'btn btn-primary btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                        'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;'
                    ])
                !!}

                {!! Form::open(['route' => ['users.purge-archived'], 'method' => 'delete', 'class' => 'form-inline']) !!}
                {!! Form::button(
                    '<i class="fa fa-2x fa-trash" style="vertical-align: middle; margin-right:0.25em;"></i>Purge Archived Users',
                    [
                        'type' => 'submit',
                        'onClick' => "return confirm('Are you sure? The archived users will be PERMANENTLY deleted!')",
                        'class' => 'btn btn-primary btn-danger col-lg-2 col-md-2 col-sm-3 col-xs-12',
                        'style' => 'margin:0.25em 0 0 0; color: white; min-width:12em;'
                    ])
                !!}
                {!! Form::close() !!}
            </div>
        @endif
    </section>
    @include('layouts.partials.site_wide_alerts._alert-content')
    <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                @include('layouts.partials.advertising.ads')
                @if(count($users) > 0)
                @include('users.table')
                @else
                    <p>There are no users currently registered. How about {!! link_to_route('register', 'signing up', [], ['title' => "Sign up for an account"]) !!} yourself?</p>
                @endif
                @include('layouts.partials.social.disqus')
            </div>
        </div>
    </div>
@endsection

@push('google-analytics')
    @include('layouts.partials.tracking._google_analytics')
@endpush

@push('jsonld_schema')

    <!-- START JSONld / schema -->
    @include('layouts.partials.seo._social_jsonld')
    <!-- END JSONld / schema -->

@endpush