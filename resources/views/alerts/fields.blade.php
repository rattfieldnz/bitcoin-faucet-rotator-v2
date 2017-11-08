<!-- Title Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('title') ? ' has-error' : '' }}">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Alert title goes here (max. 100 characters)']) !!}
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
    {!! Form::text('summary', null, ['class' => 'form-control', 'placeholder' => 'Alert summary goes here (max. 255 characters)']) !!}
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
            null,
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
    {!! Form::text('keywords', null, ['class' => 'form-control', 'placeholder' => 'Keywords goes here (max. 255 characters, separated by comma)']) !!}
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
    ?>
    <label for="alert_type_id">Alert Type:</label>
    <select class="alert_type_id" id="alert_type_id" name="alert_type_id" title="Select an alert type for your alert.">
        @foreach($alertTypes as $a)
            <option
                value="{{ $a->id }}"
                class="{{ str_replace('.', '', $a->bootstrap_alert_class) }}
                {{ !empty($alertTypeId) && $a->id == $alertTypeId ? 'selected="selected"': '' }}"
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
    ?>
    <label for="alert_icon_id">FontAwesome Alert Icon:</label>
    <select class="form-control" id="alert_icon_id" name="alert_icon_id" data-live-search="true" title="Select an icon for your alert message.">
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
    {!! Form::label('hide_alert', (empty($alert) ? 'Hide ' : 'Hidden ') . 'Alert:') !!}
    <label class="checkbox-inline">
        {!! Form::checkbox('hide_alert', '1', null) !!}
    </label>
    @if ($errors->has('hide_alert'))
        <span class="help-block">
        <strong>{{ $errors->first('hide_alert') }}</strong>
    </span>
    @endif
</div>

<!-- Show Site Wide Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('show_site_wide') ? ' has-error' : '' }}">
    {!! Form::label('show_site_wide', (empty($alert) ? 'Show ' : 'Shown ') . 'Site Wide:') !!}
    <label class="checkbox-inline">
        {!! Form::checkbox('show_site_wide', '0', null) !!}
    </label>
    @if ($errors->has('show_site_wide'))
        <span class="help-block">
        <strong>{{ $errors->first('show_site_wide') }}</strong>
    </span>
    @endif
</div>

<!-- Show Only On Home Page Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('show_only_on_home_page') ? ' has-error' : '' }}">
    {!! Form::label('show_only_on_home_page', (empty($alert) ? 'Show ' : 'Shown ') . 'Only On Home Page:') !!}
    <label class="checkbox-inline">
        {!! Form::checkbox('show_only_on_home_page', '0', null) !!}
    </label>
    @if ($errors->has('show_only_on_home_page'))
        <span class="help-block">
        <strong>{{ $errors->first('show_only_on_home_page') }}</strong>
    </span>
    @endif
</div>

<!-- Sent With Twitter Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('sent_with_twitter') ? ' has-error' : '' }}">
    {!! Form::label('sent_with_twitter', (empty($alert) ? 'Send ' : 'Sent ') . 'to Twitter:') !!}
    <label class="checkbox-inline">
        {!! Form::checkbox('sent_with_twitter', '0', null) !!}
    </label>
    @if ($errors->has('sent_with_twitter'))
        <span class="help-block">
        <strong>{{ $errors->first('sent_with_twitter') }}</strong>
    </span>
    @endif
</div>

<!-- Publish At Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('publish_at') ? ' has-error' : '' }}">
    {!! Form::label('publish_at', 'Publish At:') !!}
    {!! Form::date('publish_at', null, ['class' => 'form-control']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('publish_at'))
        <span class="help-block">
        <strong>{{ $errors->first('publish_at') }}</strong>
    </span>
    @endif
</div>

<!-- Hide At Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('hide_at') ? ' has-error' : '' }}">
    {!! Form::label('hide_at', 'Hide At:') !!}
    {!! Form::date('hide_at', null, ['class' => 'form-control']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('hide_at'))
        <span class="help-block">
        <strong>{{ $errors->first('hide_at') }}</strong>
    </span>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('alerts.index') !!}" class="btn btn-default">Cancel</a>
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
    var showSiteWide = $('#show_site_wide');
    var showOnlyOnHomePage = $('#show_only_on_home_page');
    var twitterSendOrSent = $('#sent_with_twitter');

    generateSwitch(hideAlert, true);
    toggleState(hideAlert, [showSiteWide, showOnlyOnHomePage, twitterSendOrSent]);

    generateSwitch(showSiteWide, false);
    toggleState(showSiteWide, [showOnlyOnHomePage, hideAlert]);

    generateSwitch(showOnlyOnHomePage, false);
    toggleState(showOnlyOnHomePage, [showSiteWide, hideAlert]);

    generateSwitch(twitterSendOrSent, false);

    function generateSwitch(elem, initState, onText = 'Yes', offText = 'No')
    {
        if(jQuery().bootstrapSwitch){
            if(elem !== 'undefined' && (elem.attr('type') === 'checkbox' || elem.attr('type') === 'radio')){
                elem.on('switchChange.bootstrapSwitch', function(event,  state) {
                    elem.val(parseInt(+state));
                });

                return elem.bootstrapSwitch({
                    onText: onText,
                    offText: offText,
                    state: initState
                });
            }
        }
    }

    function toggleState(checkedBox, othercheckBoxes = [])
    {
        if(jQuery().bootstrapSwitch){
            if(checkedBox !== 'undefined' && (checkedBox.attr('type') === 'checkbox' || checkedBox.attr('type') === 'radio')){
                checkedBox.on('switchChange.bootstrapSwitch', function(){
                    if(this.checked){
                        for(var i = 0; i < othercheckBoxes.length; i++){
                            othercheckBoxes[i].val(+!this.checked);
                            othercheckBoxes[i].bootstrapSwitch('state', !this.checked, true);
                        }
                    }
                });
            }
        }
    }
</script>
@endpush
