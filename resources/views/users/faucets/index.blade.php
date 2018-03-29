@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1 id="title" data-user-slug="{{ $user->slug }}">{{ $user->user_name }}'s Faucets</h1>
            @include('layouts.partials.social.addthis')
        </div>
        @if(!empty(Auth::user()) && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id))
            <div class="row zero-margin buttons-row">
                {!! Form::button(
                    '<i class="fa fa-2x fa-list" style="vertical-align: middle; margin-right:0.25em;"></i>Manage Faucets',
                    [
                        'type' => 'button',
                        'onClick' => "location.href='" . route('users.show', ['slug' => $user->slug]) . "#faucets'",
                        'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                        'style' => 'margin:0.25em 0 0 0; color: white; min-width:12em;'
                    ])
                !!}
            </div>
        @endif
    </section>
    @include('layouts.partials.site_wide_alerts._alert-content')
    <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
        @include('adminlte-templates::common.errors')
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
                @include('users.faucets.table')
                @else
                    <p>{{ $user->user_name }} has not added any faucets yet.</p>
                @endif
                @include('layouts.partials.social.disqus')
            </div>
        </div>
    </div>
@endsection

@push('google-analytics')
    @include('layouts.partials.tracking._google_analytics')
@endpush

