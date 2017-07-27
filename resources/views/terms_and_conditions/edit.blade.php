@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row" style="margin:0 0 0 0;">
            <h1>Edit Terms And Conditions</h1>
        </div>
   </section>
   <div class="content">
       <div class="clearfix"></div>
       @include('flash::message')
       <div class="clearfix"></div>
       @include('layouts.partials.navigation._breadcrumbs')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row" style="padding: 0 2em 0 2em;">
                   @if(!empty(Auth::user()) && Auth::user()->isAnAdmin())
                   {!! Form::model($termsAndConditions, ['route' => ['terms-and-conditions.update', $termsAndConditions->id], 'method' => 'patch']) !!}

                        @include('terms_and_conditions.fields')

                   {!! Form::close() !!}
                   @else
                       <p>Unfortunately, you are not authorised to edit the privacy policy; however, you can contact admin if you have inquiries about it's content.</p>
                   @endif
               </div>
           </div>
       </div>
   </div>
@endsection