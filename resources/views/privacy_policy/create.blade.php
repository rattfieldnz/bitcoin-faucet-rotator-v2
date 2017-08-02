@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row auth-page-title">
            <h1>Create Privacy Policy</h1>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'privacy-policy.store']) !!}

                        @include('privacy_policy.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
