@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Main Meta</h1>
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
                   {!! Form::model($mainMeta, ['route' => ['main-meta.update', $mainMeta->id], 'method' => 'patch']) !!}

                        @include('main_meta.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection