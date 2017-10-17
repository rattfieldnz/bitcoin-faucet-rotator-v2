@extends('layouts.app')

@section('title')
    <title>400 Bad Request!</title>
@endsection

@section('content')
    <div class="zero-margin">
        <section class="content-header">
            <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
                <h1>400 Bad Request!</h1>
            </div>
        </section>
        <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2 style="margin-top: 0;"><img src="{{ asset("/assets/images/misc/http-400-bad-request.jpg") }}" alt="HTTP Error 400 - Bad Request!" class="img-responsive"></h2>
                        </div>
                        <div class="col-lg-6">
                            <p><strong>The request cannot be fulfilled due to bad syntax.</strong></p>
                            <p><strong>Please <a href="mailto:{{ \App\Helpers\Functions\Users::adminUser()->email }}?Subject=RE:%20HTTP%20400%20to%20error%20exception.">contact the site owner</a>.</strong></p>

                            <p><strong>This error has been logged, and related information will be delivered to admin/site developer.</strong></p>

                            @if(!empty(Sentry::getLastEventID()))
                                <p><strong>Please send this ID with your support request: {{ Sentry::getLastEventID() }}.</strong></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
