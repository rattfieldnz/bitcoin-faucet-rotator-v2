<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6">
    {!! Form::label('slug', 'Slug:') !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
</div>

<!-- Summary Field -->
<div class="form-group col-sm-6">
    {!! Form::label('summary', 'Summary:') !!}
    {!! Form::text('summary', null, ['class' => 'form-control']) !!}
</div>

<!-- Alert Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('alert_type_id', 'Alert Type Id:') !!}
    {!! Form::number('alert_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Alert Icon Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('alert_icon_id', 'Alert Icon Id:') !!}
    {!! Form::number('alert_icon_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Hide Alert Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hide_alert', 'Hide Alert:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('hide_alert', false) !!}
        {!! Form::checkbox('hide_alert', '1', null) !!} 1
    </label>
</div>

<!-- Show Site Wide Field -->
<div class="form-group col-sm-6">
    {!! Form::label('show_site_wide', 'Show Site Wide:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('show_site_wide', false) !!}
        {!! Form::checkbox('show_site_wide', '1', null) !!} 1
    </label>
</div>

<!-- Show Only On Home Page Field -->
<div class="form-group col-sm-6">
    {!! Form::label('show_only_on_home_page', 'Show Only On Home Page:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('show_only_on_home_page', false) !!}
        {!! Form::checkbox('show_only_on_home_page', '1', null) !!} 1
    </label>
</div>

<!-- Sent With Twitter Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sent_with_twitter', 'Sent With Twitter:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('sent_with_twitter', false) !!}
        {!! Form::checkbox('sent_with_twitter', '1', null) !!} 1
    </label>
</div>

<!-- Publish At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('publish_at', 'Publish At:') !!}
    {!! Form::date('publish_at', null, ['class' => 'form-control']) !!}
</div>

<!-- Hide At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hide_at', 'Hide At:') !!}
    {!! Form::date('hide_at', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('alerts.index') !!}" class="btn btn-default">Cancel</a>
</div>
