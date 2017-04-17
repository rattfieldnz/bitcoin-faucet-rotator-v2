@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Ad Block
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.breadcrumbs')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'ad-block.store']) !!}

                        @include('ad_block.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
