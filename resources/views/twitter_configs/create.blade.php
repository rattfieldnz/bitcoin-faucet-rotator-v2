@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Twitter Config
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'twitter-configs.store']) !!}

                        @include('twitter_configs.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
