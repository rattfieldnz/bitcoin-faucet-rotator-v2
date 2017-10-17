@extends('layouts.app')

@section('title')
    <title>Be right back.</title>
@endsection

@section('content')
    <div class="zero-margin">
        <section class="content-header">
            <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
                <h1>Be right back.</h1>
            </div>
        </section>
        <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 style="margin-top: 0;"><img src="{{ asset("/assets/images/misc/http-503-service-unavailable.jpg") }}" alt="HTTP Error 503 - Service Unavailable!" class="img-responsive"></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
