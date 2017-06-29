@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{{ $user->user_name }}'s Panel</h1>

    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @if(!empty($message))
            <div class="alert alert-info">
                {!! $message !!}
            </div>
        @endif
        <div class="clearfix"></div>
        @include('layouts.breadcrumbs')
        @if(!empty($bob))
        <p>{{ $bob }}</p>
        @endif
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <p>Panel content goes here.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
