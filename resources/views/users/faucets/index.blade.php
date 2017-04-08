@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Faucets</h1>
        @if(Auth::user() != null)
            @if(
            (Auth::user()->is_admin == true && Auth::user()->hasRole('owner')) ||
            ($user == Auth::user() && $user->hasPermission('create-user-faucets'))
            )
                <h1 class="pull-right">
                   <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('users.faucets.create', $user->slug) !!}">Add New</a>
                </h1>
            @endif
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @if(session('message'))
            <div class="alert alert-info">
                {!! session('message') !!}
            </div>
        @endif
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('users.faucets.table')
            </div>
        </div>
    </div>
@endsection

