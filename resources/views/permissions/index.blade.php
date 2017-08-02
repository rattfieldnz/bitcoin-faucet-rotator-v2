@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Permissions</h1>
        <div class="row auth-page-title">
            <h1>Permissions</h1>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                    @include('permissions.table')
            </div>
        </div>
    </div>
@endsection

