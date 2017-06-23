@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Users</h1>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
            <h1 class="pull-right">
               <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('users.create') !!}">Add New</a>
            </h1>
            @endif
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.breadcrumbs')
        @if(Auth::user() != null && Auth::user()->isAnAdmin())
        <div class="alert alert-info">
            <p>
                <i class="fa fa-info-circle fa-2x space-right"></i>
                Your user-name is displayed to other non-admin and guest users as
                '<a href="{!! route('users.show', ['slug' => 'admin']) !!}" target="_blank">admin</a>'.
                Your first and last names are also both masked as 'Admin'. Your login name is still
                '{!! Auth::user()->user_name !!}'.
            </p>
        </div>
        @endif
        <div class="box box-primary">
            <div class="box-body">
                    @include('users.table')
            </div>
        </div>
    </div>
@endsection

