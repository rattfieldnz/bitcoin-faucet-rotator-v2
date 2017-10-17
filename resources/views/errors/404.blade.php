@extends('layouts.app')

@section('title')
    <title>404 Not Found!</title>
@endsection

@section('content')
    <div class="zero-margin">
        <section class="content-header">
            <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
                <h1>404 Not Found!</h1>
            </div>
        </section>
        <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2 style="margin-top: 0;"><img src="{{ asset("/assets/images/misc/http-404-not-found.jpg") }}" alt="HTTP Error 403 - Unauthorised/Forbidden!" class="img-responsive"></h2>
                        </div>
                        <div class="col-lg-6">
                            <p><strong>The {{ !empty($item) ? ' ' . $item . "'s " : ' ' }}page you have requested cannot be found.</strong></p>
                            <p><strong>If you believe something should exist here, please
                                    <a href="mailto:{{ \App\Helpers\Functions\Users::adminUser()->email }}?Subject=RE:%20Page%20not%20found%20error.">contact the site owner</a>.
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
