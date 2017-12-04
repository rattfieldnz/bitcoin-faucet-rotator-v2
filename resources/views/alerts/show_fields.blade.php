<div class="form-group" style="border: 0.25em solid #3c8dbc; padding: 0.5em; border-radius: 0.25em;">
    {!! Form::label('summary', 'Summary:') !!}
    <p>{!! $alert->summary !!}</p>
</div>

<!-- Content Field -->
<div class="form-group">
    {!! $alert->content !!}
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $alert->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $alert->updated_at !!}</p>
</div>

