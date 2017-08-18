@extends('layouts.app')

@section('title')
<title>403 Unauthorized!</title>
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
        color: #B0BEC5;
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
        <h2>You aren't authorized, or do not have the sufficient permissions to access this feature.</h2>
        <p><strong>Please <a href="mailto:{{ \Helpers\Functions\Users::adminUser()->email }}?Subject=RE:%20Unauthorized%20access%20to%20site%20section.">contact the site owner</a> to request access.</strong></p>
        @unless(empty($sentryID))
        <!-- Sentry JS SDK 2.1.+ required -->
            <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

            <script>
                Raven.showReportDialog({
                    eventId: '{{ $sentryID }}',

                    // use the public DSN (dont include your secret!)
                    dsn: 'https://238ec750dc3f4d05b26c9ed7f957af0b@sentry.io/53671'
                });
            </script>
        @endunless
    </div>
</div>
@endsection
