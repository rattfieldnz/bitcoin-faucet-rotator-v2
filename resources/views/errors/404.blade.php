@extends('layouts.app')

@section('title')
    <title>404 Not Found!</title>
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
            <p>
                <strong>
                    The {{ !empty($item) ? ' ' . $item . "'s " : ' ' }}page you have requested cannot be found. If you believe something should exist here, please
                    <a href="mailto:{{ \Helpers\Functions\Users::adminUser()->email }}?Subject=RE:%20Page%20not%20found%20error.">contact the site owner</a>.
                </strong>
            </p>
        </div>
    </div>
@endsection
