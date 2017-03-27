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
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
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
                    The page you have requested cannot be found. If you believe something should exist here, please
                    <a href="mailto:{{ App\Models\User::where('is_admin', '=', true)->first()->email }}?Subject=RE:%20Page%20not%20found%20error.">contact the site owner</a>.
                </strong>
            </p>
        </div>
    </div>
@endsection
