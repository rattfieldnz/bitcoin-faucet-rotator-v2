@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Faucet</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="row">
            @include('users.faucets.table')
        </div>
    </div>
@endsection
