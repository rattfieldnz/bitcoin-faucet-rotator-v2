@extends('layouts.app')

@section('title')
    <title>500 Server Error!</title>
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
            <h1 class="title">Error 418 - I'm a Teapot!</h1>

            <p><img src="/assets/images/misc/http-418-im-a-teapot.jpg" alt="HTTP Error 418 - I'm a teapot!" style="width: auto;"></p>

            @if(!empty(Auth::user()) && Auth::user()->isAnAdmin())
                @if(!empty($message))
                    <h3>A brief message describing the error is below:</h3>
                    <ul>
                        <li><strong>{{ $message }}</strong></li>
                    </ul>

                @endif
            @else
                <p><strong>Please <a href="mailto:{{ \Helpers\Functions\Users::adminUser()->email }}?Subject=RE:%20Error%20418%20issue.">contact the site owner</a> for further information.</strong></p>
            @endif

            <p><strong>This error has been logged, and related information will be delivered to admin/site developer.</strong></p>

            @if(!empty(Sentry::getLastEventID()))
                <p><strong>Please send this ID with your support request: {{ Sentry::getLastEventID() }}.</strong></p>
            @endif

        </div>
    </div>
@endsection
