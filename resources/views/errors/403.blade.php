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
        <p><strong>You aren't authorized, or do not have the sufficient permissions to access this feature.</strong></p>
        <p><strong>Please <a href="mailto:{{ App\Models\User::where('is_admin', '=', true)->first()->hasRole('owner')->email }}?Subject=RE:%20Unauthorized%20access%20to%20site%20section.">contact the site owner</a> to request access.</strong></p>
    </div>
</div>
@endsection
