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
            color: #B0BEC5;
            font-weight: 100;
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
            <h1 class="title">Error 500 - Oops!</h1>
            <h2>Something has been broken :(.</h2>

            @if(!empty(Auth::user()) && Auth::user()->isAnAdmin())
            <h3>A brief message describing the error is below:</h3>
            <ul>
                <li><strong>{{ $message }}</strong></li>
            </ul>
            @endif
            <p><strong>This error has been logged, and related information will be delivered to admin/site developer.</strong></p>
        </div>
    </div>
@endsection
