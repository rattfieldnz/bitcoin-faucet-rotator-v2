<!-- Ad Content Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('ad_content', 'Ad Content:') !!}
    {!! Form::textarea('ad_content', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('ad-blocks.index') !!}" class="btn btn-default">Cancel</a>
</div>
