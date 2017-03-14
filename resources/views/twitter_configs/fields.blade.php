<!-- Consumer Key Field -->
<div class="form-group col-sm-6">
    {!! Form::label('consumer_key', 'Consumer Key:') !!}
    {!! Form::text('consumer_key', null, ['class' => 'form-control']) !!}
</div>

<!-- Consumer Key Secret Field -->
<div class="form-group col-sm-6">
    {!! Form::label('consumer_key_secret', 'Consumer Key Secret:') !!}
    {!! Form::text('consumer_key_secret', null, ['class' => 'form-control']) !!}
</div>

<!-- Access Token Field -->
<div class="form-group col-sm-6">
    {!! Form::label('access_token', 'Access Token:') !!}
    {!! Form::text('access_token', null, ['class' => 'form-control']) !!}
</div>

<!-- Access Token Secret Field -->
<div class="form-group col-sm-6">
    {!! Form::label('access_token_secret', 'Access Token Secret:') !!}
    {!! Form::text('access_token_secret', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('twitter-configs.index') !!}" class="btn btn-default">Cancel</a>
</div>
