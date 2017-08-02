@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row auth-page-title">
            <h1>Add a Faucet</h1>
        </div>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    @include('users.faucets.table')
                </div>
            </div>
        </div>
    </div>
@endsection
