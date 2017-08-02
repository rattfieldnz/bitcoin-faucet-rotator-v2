@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row auth-page-title">
            <h1>Edit '{{ $faucet->name }}' Faucet</h1>
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
                   {!! Form::model($faucet, ['route' => ['faucets.update', $faucet->slug], 'method' => 'patch']) !!}

                        @include('faucets.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection