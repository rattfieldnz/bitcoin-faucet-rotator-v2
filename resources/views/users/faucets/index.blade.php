@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{{ $user->user_name }}'s Faucets</h1>
        @if(Auth::user() != null)
            @if(
            Auth::user()->isAnAdmin() ||
            ($user == Auth::user() && $user->hasPermission('create-user-faucets'))
            )
            <h1 class="pull-right">
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('users.faucets.create', $user->slug) !!}">Add New</a>
            </h1>
            @endif
        @endif
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                @if(count($faucets) > 0)
                @include('users.faucets.table')
                @else
                    <p>{{ $user->user_name }} has not added any faucets yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('google-analytics')
    @include('partials.google_analytics')
@endsection

