@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Ad Block
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'ad-blocks.store']) !!}

                        @include('ad_blocks.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
