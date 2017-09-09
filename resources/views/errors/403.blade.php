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
        <h2>You aren't authorized, or do not have the sufficient permissions to access this feature.</h2>
        <p><strong>Please <a href="mailto:{{ \App\Helpers\Functions\Users::adminUser()->email }}?Subject=RE:%20Unauthorized%20access%20to%20site%20section.">contact the site owner</a> to request access.</strong></p>

        <p><strong>This error has been logged, and related information will be delivered to admin/site developer.</strong></p>

        @if(!empty(Sentry::getLastEventID()))
            <p><strong>Please send this ID with your support request: {{ Sentry::getLastEventID() }}.</strong></p>
        @endif
    </div>
</div>
@endsection
