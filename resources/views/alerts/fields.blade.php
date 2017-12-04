<?php
    $currentDate = \Carbon\Carbon::now();
    $formattedCurrentDate = $currentDate->format('d/m/Y H:i:s');
    $futureDate = $currentDate->addDay();
    $formattedFutureDate = $futureDate->format('d/m/Y H:i:s');
?>
@if(!empty($alert))
{!! Form::hidden('id', !empty($alert) ? $alert->id : null) !!}
@endif
<!-- Title Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('title') ? ' has-error' : '' }}">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', old('title',$alert->title ?? null), ['class' => 'form-control', 'placeholder' => 'Alert title goes here (max. 100 characters)']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('title'))
        <span class="help-block">
            <strong>{{ $errors->first('title') }}</strong>
        </span>
    @endif
</div>

<!-- Summary Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('summary') ? ' has-error' : '' }}">
    {!! Form::label('summary', 'Summary:') !!}
    {!! Form::text('summary', old('summary',$alert->summary ?? null), ['class' => 'form-control', 'placeholder' => 'Alert summary goes here (max. 255 characters)']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('summary'))
        <span class="help-block">
            <strong>{{ $errors->first('summary') }}</strong>
        </span>
    @endif
</div>

<!-- Content body Field -->
<div class="form-group col-sm-12 has-feedback{{ $errors->has('content') ? ' has-error' : '' }}">
    {!! Form::label('alert_content', 'Content:') !!}
    {!!
        Form::textarea(
            'content',
            old('summary',$alert->content ?? null),
            [
                'class' => 'form-control',
                'id' => 'alert_content',
                'placeholder' => "Content body of alert goes here."
            ]
        )
    !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('content'))
        <span class="help-block">
            <strong>{{ $errors->first('content') }}</strong>
        </span>
    @endif
</div>

<!-- Keywords Field -->
<div class="form-group col-sm-12 has-feedback{{ $errors->has('keywords') ? ' has-error' : '' }}">
    {!! Form::label('keywords', 'Keywords:') !!}
    {!! Form::text('keywords', old('keywords',$alert->keywords ?? null), ['class' => 'form-control', 'placeholder' => 'Keywords goes here (max. 255 characters, separated by comma)']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('keywords'))
        <span class="help-block">
            <strong>{{ $errors->first('keywords') }}</strong>
        </span>
    @endif
</div>

<!-- Alert Type Id Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('alert_type_id') ? ' has-error' : '' }}">
    <?php
        $alertTypes = \App\Models\AlertType::all();
        $defaultAlertType = \App\Models\AlertType::where('name', '=', 'info')->first();
        $alertTypeId = old('alert_type_id', $alert->alert_type_id ?? null);
    ?>
    <label for="alert_type_id">Alert Type:</label>
    <select class="alert_type_id" id="alert_type_id" name="alert_type_id">
        @foreach($alertTypes as $a)
            <option
                value="{{ $a->id }}"
                class="alert {{ str_replace('.', '', $a->bootstrap_alert_class) }}"
                style="margin: 0.25em 0 0.25em 0;"
                {{ !empty($alertTypeId) && $a->id  == $alertTypeId || $defaultAlertType->id == $a->id ? 'selected="selected"': '' }}
            >
                {{ ucfirst($a->name) }}
            </option>
        @endforeach
    </select>
    @if ($errors->has('alert_type_id'))
        <span class="help-block">
            <strong>{{ $errors->first('alert_type_id') }}</strong>
        </span>
    @endif
</div>

<!-- Alert Icon Id Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('alert_icon_id') ? ' has-error' : '' }}">
    <?php
        $icons = \App\Models\AlertIcon::all();
        $defaultIcon = \App\Models\AlertIcon::where('icon_class', '=', 'fa-info')->first();
        $alertIconId = old('alert_icon_id', $alert->alert_icon_id ?? null);
    ?>
    <label for="alert_icon_id">FontAwesome Alert Icon:</label>
    <select class="form-control" id="alert_icon_id" name="alert_icon_id" data-live-search="true">
        @foreach($icons as $icon)
        <option
            value="{{ $icon->id }}"
            data-icon="fa {{ $icon->icon_class }} fa-2x"
            {{ !empty($alertIconId) && $icon->id == $alertIconId || $icon->id == $defaultIcon->id ? 'selected="selected"': '' }}
        >
            ({{ $icon->icon_class }})
        </option>
        @endforeach
    </select>
    @if ($errors->has('alert_icon_id'))
        <span class="help-block">
            <strong>{{ $errors->first('alert_icon_id') }}</strong>
        </span>
    @endif
</div>

<!-- Hide Alert Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('hide_alert') ? ' has-error' : '' }}">
    <?php
        $hideAlert = old('hide_alert', $alert->hide_alert ?? null);
    ?>
    {!! Form::label('hide_alert', (empty($alert) ? 'Hide ' : 'Hidden ') . 'Alert From Home Page:') !!}
    <label class="checkbox-inline">
        {!! Form::checkbox('hide_alert', intval($hideAlert), boolval($hideAlert)) !!}
    </label>
    @if ($errors->has('hide_alert'))
        <span class="help-block">
            <strong>{{ $errors->first('hide_alert') }}</strong>
        </span>
    @endif
</div>

<!-- Sent With Twitter Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('sent_with_twitter') ? ' has-error' : '' }}">
    <?php
        $tweetSent = old('sent_with_twitter', $alert->sent_with_twitter ?? null);
    ?>
    {!! Form::label('sent_with_twitter', (empty($alert) ? 'Send ' : 'Sent ') . 'to Twitter?:') !!}
    <label class="checkbox-inline">
        {!! Form::checkbox('sent_with_twitter', intval($tweetSent), boolval($tweetSent)) !!}
    </label>
    @if ($errors->has('sent_with_twitter'))
        <span class="help-block">
            <strong>{{ $errors->first('sent_with_twitter') }}</strong>
        </span>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-6">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('alerts.index') !!}" class="btn btn-default">Cancel</a>
</div>

<!-- Twitter Message Field -->
<div id="twitter-message-field" class="form-group col-sm-6 has-feedback{{ $errors->has('twitter_message') ? ' has-error' : '' }}">
    <?php
        $tweet = old('twitter_message', $alert->twitter_message ?? null);
    ?>
    {!! Form::label('twitter_message', 'Tweet*:') !!}
    {!! Form::text('twitter_message', $tweet, ['class' => 'form-control', 'placeholder' => 'Enter tweet here (140 characters max. URL\'s shortened to 23 characters via Twitter).']) !!}
    <span class="fa fa-twitter fa-2x form-control-feedback alert-tweet-field"></span>
    <p><strong>* <small>Available placeholders are: [alert_title], [alert_url], [alert_summary], [alert_published_at].</small></strong></p>
    @if ($errors->has('twitter_message'))
        <span class="help-block">
            <strong>{{ $errors->first('twitter_message') }}</strong>
        </span>
    @endif
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset("/assets/css/bootstrap-switch/bootstrap-switch.min.css?" . rand()) }}">
    <link rel="stylesheet" href="{{ asset("/assets/css/bootstrap-select/bootstrap-select.min.css?" . rand()) }}">
@endpush

@push('scripts')
<script src="{{ asset("/assets/js/bootstrap-switch/bootstrap-switch.min.js") . "?" . rand() }}"></script>
<script src="{{ asset("/assets/js/bootstrap-select/bootstrap-select.min.js") . "?" . rand() }}"></script>
<script src="{{ asset("/assets/js/ckeditor/ckeditor.js?") . "?" . rand() }}"></script>
<script>
    CKEDITOR.replace('alert_content');

    $('#alert_icon_id').selectpicker();
    $('#alert_type_id').selectpicker();

    var hideAlert = $('#hide_alert');
    var twitterSendOrSent = $('#sent_with_twitter');
    var tweetField = $('#twitter-message-field');
    tweetField.hide();

    generateSwitch(hideAlert, {{ boolval(intval($hideAlert)) }});
    generateSwitch(twitterSendOrSent, {{ boolval(intval($tweetSent)) }});

    twitterSendOrSent.on('switchChange.bootstrapSwitch', function(event,  state) {
        if(state){
            tweetField.show();
        } else{
            tweetField.hide();
        }
    });
</script>
@endpush
