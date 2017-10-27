<!-- Facebook Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('facebook_url', 'Facebook Url:') !!}
    {!! Form::text('facebook_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Twitter Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('twitter_url', 'Twitter Url:') !!}
    {!! Form::text('twitter_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Reddit Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reddit_url', 'Reddit Url:') !!}
    {!! Form::text('reddit_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Google Plus Url Field -->
@if(!empty($socialLinks))
    {!! Form::hidden('id', $socialLinks->id ) !!}
@endif

@if(!empty($adminUser))
    {!! Form::hidden('user_id', $adminUser->id ) !!}
@endif
<div class="form-group col-sm-6">
    {!! Form::label('google_plus_url', 'Google Plus Url:') !!}
    {!! Form::text('google_plus_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Youtube Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('youtube_url', 'Youtube Url:') !!}
    {!! Form::text('youtube_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Tumblr Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tumblr_url', 'Tumblr Url:') !!}
    {!! Form::text('tumblr_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Vimeo Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vimeo_url', 'Vimeo Url:') !!}
    {!! Form::text('vimeo_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Vkontakte Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vkontakte_url', 'Vkontakte Url:') !!}
    {!! Form::text('vkontakte_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Sinaweibo Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sinaweibo_url', 'Sinaweibo Url:') !!}
    {!! Form::text('sinaweibo_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Xing Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('xing_url', 'Xing Url:') !!}
    {!! Form::text('xing_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('settings') . '#social-links' !!}" class="btn btn-default">Cancel</a>
</div>
