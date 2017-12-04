<div class="form-group">
    <h2 style="margin-top: 0;">Summary:</h2>
    <p style="font-size:1.25em;"><strong><em>{!! $alert->summary !!}</em></strong></p>
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

