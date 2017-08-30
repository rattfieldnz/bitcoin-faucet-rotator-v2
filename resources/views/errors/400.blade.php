@extends('layouts.app')

@section('title')
    <title>400 Bad Request!</title>
@endsection

@section('css')
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #333333;
            font-family: 'Lato';
        }

        .content {
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="content">
            <h1 class="title">Sorry!</h1>
            <h2>The request cannot be fulfilled due to bad syntax.</h2>
            <p><strong>Please <a href="mailto:{{ \Helpers\Functions\Users::adminUser()->email }}?Subject=RE:%20HTTP%20400%20to%20error%20exception.">contact the site owner</a> to request access.</strong></p>

            <p><strong>This error has been logged, and related information will be delivered to admin/site developer.</strong></p>

            @if(!empty(Sentry::getLastEventID()))
                <p><strong>Please send this ID with your support request: {{ Sentry::getLastEventID() }}.</strong></p>
            @endif
        </div>
    </div>
@endsection
