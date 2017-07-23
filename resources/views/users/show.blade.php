@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{{ $user->user_name }}'s Profile</h1>
        @if(Auth::user() != null)
            <p class="pull-right">
                @if(Auth::user() == $user ||Auth::user()->isAnAdmin())
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px;margin-left:10px;" href="{!! route('users.edit', ['slug' => $user->slug]) !!}">Edit Current User</a>
                @endif

                @if(Auth::user()->isAnAdmin())
                    <a class="btn btn-primary btn-success pull-right" style="margin-top: -10px;margin-bottom: 5px;margin-left:10px;" href="{!! route('users.create') !!}">Add New User</a>
                    @if($user->isDeleted())
                        {!! Form::open(['route' => ['users.delete-permanently', $user->slug], 'method' => 'delete', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Permanently Delete', ['type' => 'submit', 'class' => 'btn btn-danger', 'style' => 'margin-top:-10px;margin-bottom: 5px;', 'onclick' => "return confirm('Are you sure? The user will be PERMANENTLY deleted!')"]) !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['users.restore', $user->slug], 'method' => 'patch', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Restore', ['type' => 'submit', 'class' => 'btn btn-info', 'style' => 'margin-top: -10px;margin-bottom:5px;margin-right:10px;', 'onclick' => "return confirm('Are you sure you want to restore this archived user?')"]) !!}
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['route' => ['users.destroy', 'slug' => $user->slug], 'method' => 'delete', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Archive/Delete Current User', ['type' => 'submit', 'class' => 'btn btn-warning', 'style' => 'margin-top: -10px;margin-bottom: 5px;', 'onclick' => "return confirm('Are you sure you want to archive this user?')"]) !!}
                        {!! Form::close() !!}
                    @endif
                @endif
            </p>
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
                <div class="row" style="padding-left: 1em; padding-right: 1em;">
                    <div id="tabs">
                        <ul>
                            <li><a href="#profile">Profile</a></li>
                            <li><a href="#faucets">Faucets</a></li>
                            <li><a href="#tabs-3">Aenean lacinia</a></li>
                        </ul>
                        <div id="profile">
                            @include('users.panel._profile')
                        </div>
                        <div id="faucets">
                            @include('users.panel._faucets')
                        </div>
                        <div id="tabs-3">
                            <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
                            <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .ui-state-active,
        .ui-widget-content .ui-state-active,
        .ui-widget-header .ui-state-active,
        a.ui-button:active,
        .ui-button:active,
        .ui-button.ui-state-active:hover {
            border: 0px;
            background: #3c8dbc;
            font-weight: normal;
            color: #ffffff;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $( function() {
            $( "#tabs" ).tabs();
        } );
    </script>
@endsection

@section('google-analytics')
    @include('partials.google_analytics')
@endsection