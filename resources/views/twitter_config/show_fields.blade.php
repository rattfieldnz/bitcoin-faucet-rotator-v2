<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $twitterConfig->id !!}</p>
</div>

<!-- Consumer Key Field -->
<div class="form-group">
    {!! Form::label('consumer_key', 'Consumer Key:') !!}
    <p>{!! $twitterConfig->consumer_key !!}</p>
</div>

<!-- Consumer Key Secret Field -->
<div class="form-group">
    {!! Form::label('consumer_key_secret', 'Consumer Key Secret:') !!}
    <p>{!! $twitterConfig->consumer_key_secret !!}</p>
</div>

<!-- Access Token Field -->
<div class="form-group">
    {!! Form::label('access_token', 'Access Token:') !!}
    <p>{!! $twitterConfig->access_token !!}</p>
</div>

<!-- Access Token Secret Field -->
<div class="form-group">
    {!! Form::label('access_token_secret', 'Access Token Secret:') !!}
    <p>{!! $twitterConfig->access_token_secret !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $twitterConfig->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $twitterConfig->updated_at !!}</p>
</div>

