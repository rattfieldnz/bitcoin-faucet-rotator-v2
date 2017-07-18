@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Users</h1>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
            <h1 class="pull-right">
               <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('users.create') !!}">Add New</a>
            </h1>
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
                @if(count($users) > 0)
                @include('users.table')
                @else
                    <p>There are no users currently registered. How about {!! link_to_route('register', 'signing up', [], ['title' => "Sign up for an account"]) !!} yourself?</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('google-analytics')
    @include('partials.google_analytics')
@endsection