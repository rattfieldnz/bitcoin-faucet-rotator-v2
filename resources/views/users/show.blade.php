@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row {{ !empty(Auth::user()) ? 'user-profile-main-heading' : 'guest-user-profile-main-heading' }}">
            <h1 id="title" data-user-slug="{{ $user->slug }}">{{ $user->user_name }}'s Profile</h1>
            <div style="margin-left: 0.8em;">
                @include('layouts.partials.social.addthis')
            </div>
        </div>
        <div class="row zero-margin">
            @if(Auth::user() != null)
                @if(Auth::user() == $user ||Auth::user()->isAnAdmin())
                    <?php
                    if (Auth::user() == $user) {
                        $buttonText = "Edit Your Profile";
                    } else {
                        $buttonText = "Edit Current User";
                    }
                    ?>
                    {!! Form::button(
                        '<i class="fa fa-2x fa-edit" style="vertical-align: middle; margin-right:0.25em;"></i>' . $buttonText,
                        [
                            'type' => 'button',
                            'onClick' => "location.href='" . route('users.edit', ['slug' => $user->slug]) . "'",
                            'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                            'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;'
                        ])
                    !!}
                @endif

                @if(Auth::user()->isAnAdmin())

                    {!! Form::button(
                        '<i class="fa fa-2x fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New User',
                        [
                            'type' => 'button',
                            'onClick' => "location.href='" . route('users.create') . "'",
                            'class' => 'btn btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                            'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;'
                        ])
                    !!}

                    @if($user->isDeleted())
                        {!! Form::open(['route' => ['users.delete-permanently', $user->slug], 'method' => 'delete', 'class' => 'form-inline']) !!}
                        {!! Form::button(
                            '<i class="fa fa-2x fa-trash" style="vertical-align: middle; margin-right:0.25em;"> </i> Permanently Delete',
                            [
                                'type' => 'submit',
                                'class' => 'btn btn-danger col-lg-2 col-md-2 col-sm-3 col-xs-12',
                                'style' => 'margin:0.25em 0.5em 0 0; min-width:12em;',
                                'onclick' => "return confirm('Are you sure? The user will be PERMANENTLY deleted!')"
                            ])
                        !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['users.restore', $user->slug], 'method' => 'patch', 'class' => 'form-inline']) !!}
                        {!! Form::button(
                            '<i class="fa fa-2x fa-refresh" style="vertical-align: middle; margin-right:0.25em;"> </i> Restore User',
                            [
                                'type' => 'submit',
                                'class' => 'btn btn-info col-lg-2 col-md-2 col-sm-3 col-xs-12',
                                'style' => 'margin:0.25em 0.5em 0 0; min-width:12em;',
                                'onclick' => "return confirm('Are you sure you want to restore this archived user?')"
                            ])
                        !!}
                        {!! Form::close() !!}
                    @else
                        @if(!$user->isAnAdmin())
                        {!! Form::open(['route' => ['users.destroy', 'slug' => $user->slug], 'method' => 'delete', 'class' => 'form-inline']) !!}
                        {!! Form::button(
                            '<i class="fa fa-2x fa-trash" style="vertical-align: middle; margin-right:0.25em;"> </i> Archive Current User',
                            [
                                'type' => 'submit',
                                'class' => 'btn btn-warning col-lg-2 col-md-2 col-sm-3 col-xs-12',
                                'style' => 'margin:0.25em 0.5em 0 0; min-width:12em;',
                                'onclick' => "return confirm('Are you sure you want to archive this user?')"
                            ])
                        !!}
                        {!! Form::close() !!}
                        @endif
                    @endif
                @endif
            @endif
        </div>
    </section>
    <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
        <div class="clearfix"></div>
        @include('flash::message')
        @if(!empty($message))
            <div class="alert alert-info">
                {!! $message !!}
            </div>
        @endif
        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row profile-padding">
                    @include('layouts.partials.advertising.ads')
                    <div id="tabs">
                        <ul>
                            <li><a href="#profile">Profile</a></li>
                            <li><a href="#faucets">Faucets</a></li>
                        </ul>
                        <div id="profile">
                            @include('users.panel._profile')
                        </div>
                        <div id="faucets">
                            @include('users.panel._faucets')
                        </div>
                    </div>
                    @include('layouts.partials.social.disqus')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $( function() {
            $( "#tabs" ).tabs();
        } );
    </script>
@endpush

@push('google-analytics')
    @include('layouts.partials.tracking._google_analytics')
@endpush