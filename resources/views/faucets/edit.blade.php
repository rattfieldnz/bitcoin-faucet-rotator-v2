@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Faucet
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="clearfix"></div>

       @include('flash::message')

       <div class="clearfix"></div>
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