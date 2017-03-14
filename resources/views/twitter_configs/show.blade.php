@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Twitter Config
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('twitter_configs.show_fields')
                    <a href="{!! route('twitter-configs.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
