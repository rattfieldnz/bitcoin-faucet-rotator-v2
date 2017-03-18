<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $adBlock->id !!}</p>
</div>

<!-- Ad Content Field -->
<div class="form-group">
    {!! Form::label('ad_content', 'Ad Content:') !!}
    <p>{!! $adBlock->ad_content !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $adBlock->user_id !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $adBlock->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $adBlock->updated_at !!}</p>
</div>

