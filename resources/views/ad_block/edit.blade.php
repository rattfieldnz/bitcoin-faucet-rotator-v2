@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row auth-page-title">
            <h1>Manage Ad Block</h1>
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
                   {!! Form::model($adBlock, ['route' => ['ad-block.update', $adBlock->id], 'method' => 'patch']) !!}

                        @include('ad_block.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection