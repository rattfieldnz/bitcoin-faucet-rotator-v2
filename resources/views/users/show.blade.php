@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">User - '{{ $user->user_name }}'</h1>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <p class="pull-right">
                    <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px;margin-left:10px;" href="{!! route('users.create') !!}">Add New</a>
                    <a class="btn btn-default pull-right" style="margin-top: -10px;margin-bottom: 5px;margin-left:10px;" href="{!! route('users.edit', ['slug' => $user->slug]) !!}">Edit</a>
                    @if($user->isDeleted())
                        {!! Form::open(['route' => ['users.delete-permanently', $user->slug], 'method' => 'delete', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Permanently Delete', ['type' => 'submit', 'class' => 'btn btn-danger', 'style' => 'margin-top:-10px;margin-bottom: 5px;', 'onclick' => "return confirm('Are you sure? The user will be PERMANENTLY deleted!')"]) !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['users.restore', $user->slug], 'method' => 'patch', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Restore', ['type' => 'submit', 'class' => 'btn btn-info', 'style' => 'margin-top: -10px;margin-bottom:5px;margin-right:10px;', 'onclick' => "return confirm('Are you sure you want to restore this archived user?')"]) !!}
                        {!! Form::close() !!}
                    @else
                        @if(!$user->isAnAdmin())
                        {!! Form::open(['route' => ['users.destroy', 'slug' => $user->slug], 'method' => 'delete', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Archive/Delete', ['type' => 'submit', 'class' => 'btn btn-warning', 'style' => 'margin-top: -10px;margin-bottom: 5px;', 'onclick' => "return confirm('Are you sure you want to archive this user?')"]) !!}
                        {!! Form::close() !!}
                        @endif
                    @endif
                </p>
            @endif
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @if(!empty($message))
            <div class="alert alert-info">
                {!! $message !!}
            </div>
        @endif
        <div class="clearfix"></div>
        @include('layouts.breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('users.show_fields')
                    <a href="{!! route('users.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
