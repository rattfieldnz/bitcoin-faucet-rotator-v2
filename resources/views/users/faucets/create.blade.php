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
                <div class="row zero-margin">
                    @if(count($faucets) == 0)
                        <p>There are no more faucets to add. All available faucets are being used.</p>
                    @else
                        @include('users.faucets.table2')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
