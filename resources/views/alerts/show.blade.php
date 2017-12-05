@extends('layouts.app')

@section('content')
    @php
        $alertType = $alert->alertType()->first();
        $alertClass = $alertType->bootstrap_alert_class;
        $alertIcon = $alert->alertIcon()->first();
    @endphp
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1 id="title">
                <i
                    class="fa {!!  $alertIcon->icon_class !!} alert {!! str_replace('.', '', $alertClass) !!} alert-custom-icon"
                    title="{!! str_replace('fa-', '', $alertIcon->icon_class) !!} ({!! $alertType->name !!})"
                ></i>
                Alert - {!! $alert->title !!}
            </h1>
            @include('layouts.partials.social.addthis')
        </div>
        <div class="row zero-margin">
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())

                {!! Form::button(
                    '<i class="fa fa-2x fa-edit" style="vertical-align: middle; margin-right:0.25em;"></i>Edit Current Alert',
                    [
                        'type' => 'button',
                        'onClick' => "location.href='" . route('alerts.edit', ['slug' => $alert->slug]) . "'",
                        'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-2 col-xs-12',
                        'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;'
                    ])
                !!}

                {!! Form::button(
                    '<i class="fa fa-2x fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New Alert',
                    [
                        'type' => 'button',
                        'onClick' => "location.href='" . route('alerts.create') . "'",
                        'class' => 'btn btn-success col-lg-2 col-md-2 col-sm-2 col-xs-12',
                        'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;'
                    ])
                !!}

                @if($alert->isDeleted())
                    {!! Form::open(['route' => ['alerts.delete-permanently', $alert->id], 'method' => 'delete', 'class' => 'form-inline']) !!}
                    {!! Form::button(
                        '<i class="fa fa-2x fa-trash" style="vertical-align: middle; margin-right:0.25em;"></i>Permanently Delete',
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-danger col-lg-2 col-md-2 col-sm-2 col-xs-12',
                            'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;',
                            'onclick' => "return confirm('Are you sure? The alert will be PERMANENTLY deleted!')"
                        ])
                    !!}
                    {!! Form::close() !!}
                    {!! Form::open(['route' => ['alerts.restore', $alert->id], 'method' => 'patch', 'class' => 'form-inline']) !!}
                    {!! Form::button(
                        '<i class="fa fa-2x fa-refresh" style="vertical-align: middle; margin-right:0.25em;"></i>Restore Current Alert',
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-info col-lg-2 col-md-2 col-sm-2 col-xs-12',
                            'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;',
                            'onclick' => "return confirm('Are you sure you want to restore this archived faucet?')"
                        ])
                    !!}
                    {!! Form::close() !!}
                @else
                    {!! Form::open(['route' => ['alerts.destroy', 'slug' => $alert->id], 'method' => 'delete', 'class' => 'form-inline']) !!}
                    {!! Form::button(
                        '<i class="fa fa-2x fa-trash" style="vertical-align: middle; margin-right:0.25em;"></i>Archive Alert',
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-warning col-lg-2 col-md-2 col-sm-2 col-xs-12',
                            'style' => 'margin:0.25em 0.5em 0 0; color: white; min-width:12em;',
                            'onclick' => "return confirm('Are you sure you want to archive this alert?')"
                        ])
                    !!}
                    {!! Form::close() !!}
                @endif
            @endif
        @endif
        </div>
    </section>
    <div class="content {{ empty(Auth::user()) ? 'content-guest' : '' }}">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')

        <div class="box box-primary">
            <div class="box-body">
                <div class="row zero-margin">
                    @include('layouts.partials.advertising.ads')
                    @include('alerts.show_fields')
                    @include('layouts.partials.social.disqus')
                </div>
            </div>
        </div>
    </div>
@endsection
