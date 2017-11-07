<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $alert->title !!}</p>
</div>

<!-- Alert Type Id Field -->
<div class="form-group">
    {!! Form::label('alert_type_id', 'Alert Type Id:') !!}
    <p>{!! $alert->alert_type_id !!}</p>
</div>

<!-- Summary Field -->
<div class="form-group">
    {!! Form::label('summary', 'Summary:') !!}
    <p>{!! $alert->summary !!}</p>
</div>

<!-- Content Field -->
<div class="form-group">
    {!! Form::label('content', 'Content:') !!}
    {!! $alert->content !!}
</div>

<!-- Alert Icon Id Field -->
<div class="form-group">
    {!! Form::label('alert_icon_id', 'Alert Icon Id:') !!}
    <p>{!! $alert->alert_icon_id !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $alert->created_at !!}</p>
</div>

<!-- Publish At Field -->
<div class="form-group">
    {!! Form::label('publish_at', 'Published At:') !!}
    <p>{!! $alert->publish_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $alert->updated_at !!}</p>
</div>

