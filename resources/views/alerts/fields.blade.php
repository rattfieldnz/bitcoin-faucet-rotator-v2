<?php
use App\Models\AlertIcon;use App\Models\AlertType;use Carbon\Carbon;$currentDate = Carbon::now();
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
        $alertTypes = AlertType::all();
        $defaultAlertType = AlertType::where('name', '=', 'info')->first();
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
        $icons = AlertIcon::all();
        $defaultIcon = AlertIcon::where('icon_class', '=', 'fa-info')->first();
        $alertIconId = old('alert_icon_id', !empty($alert) ? $alert->alert_icon_id : $defaultIcon->id);
    ?>
    <label for="alert_icon_id">FontAwesome Alert Icon:</label>
    <select class="form-control" id="alert_icon_id" name="alert_icon_id" data-live-search="true">
        @foreach($icons as $icon)
        <option
            value="{{ $icon->id }}"
            data-icon="fa {{ $icon->icon_class }} fa-2x"
            {{ !empty($alertIconId) && $icon->id == $alertIconId ? 'selected="selected"': '' }}
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
        $hideAlert = intval(old('hide_alert', !empty($alert) ? $alert->hide_alert : false));
    ?>
    {!! Form::label('hide_alert', (empty($alert) ? 'Hide ' : 'Hidden ') . 'Alert From Home Page:', ['style' => 'margin-right:1em;']) !!}
    <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-primary{{ $hideAlert == 1 ? ' active' : '' }}">
            {{ Form::radio('hide_alert', 1, $hideAlert == 1 ? true : false, ['id' => 'hide_alert_yes', $hideAlert == 1 ? 'checked' : '']) }} Yes
        </label>
        <label class="btn btn-primary{{ $hideAlert == 0 ? ' active' : '' }}">
            {{ Form::radio('hide_alert', 0, $hideAlert == 0 ? true : false, ['id' => 'hide_alert_no', $hideAlert == 0 ? 'checked' : '']) }} No
        </label>
    </div>

    @if ($errors->has('hide_alert'))
        <span class="help-block">
            <strong>{{ $errors->first('hide_alert') }}</strong>
        </span>
    @endif
</div>

<!-- Sent With Twitter Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('sent_with_twitter') ? ' has-error' : '' }}">
    <?php
        $tweetSent = intval(old('sent_with_twitter', !empty($alert) ? $alert->sent_with_twitter : false));
    ?>
    {!! Form::label('sent_with_twitter', (empty($alert) ? 'Send ' : 'Sent ') . 'to Twitter?:', ['style' => 'margin-right:1em;']) !!}
    <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-primary{{ $tweetSent == 1 ? ' active' : '' }}">
            {{ Form::radio('sent_with_twitter', 1, $tweetSent == 1 ? true : false, ['id' => 'sent_with_twitter_yes', $tweetSent == 1 ? 'checked' : '']) }} Yes
        </label>
        <label class="btn btn-primary{{ $tweetSent == 0 ? ' active' : '' }}">
            {{ Form::radio('sent_with_twitter', 0, $tweetSent == 0 ? true : false, ['id' => 'sent_with_twitter_no', $tweetSent == 0 ? 'checked' : '']) }} No
        </label>
    </div>
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

@push('scripts')
<script src="{{ asset("/assets/js/ckeditor/ckeditor.js?") . "?" . rand() }}"></script>
<script>
    CKEDITOR.replace('alert_content');

    $('#alert_icon_id').selectpicker();
    $('#alert_type_id').selectpicker();

    var tweetField = $('#twitter-message-field');
    var sentWithTwitter = $('input[type=radio][name=sent_with_twitter]');

    if(parseInt(sentWithTwitter.val()) === 0){
        tweetField.hide();
    }

    sentWithTwitter.on('change', function() {
        switch(parseInt($(this).val())) {
            case 1:
                tweetField.show();

                break;
            case 0:
                tweetField.hide();
                break;
        }
    });
</script>
@endpush
