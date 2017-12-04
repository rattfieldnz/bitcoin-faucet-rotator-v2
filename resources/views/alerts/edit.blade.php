@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1 id="title">Edit alert - '{!! $alert->title !!}'</h1>
        </div>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($alert, ['route' => ['alerts.update', $alert->slug], 'method' => 'patch']) !!}

                        @include('alerts.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection