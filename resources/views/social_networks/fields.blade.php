@if(!empty($socialLinks))
    {!! Form::hidden('id', $socialLinks->id ) !!}
@endif

@if(!empty($adminUser))
    {!! Form::hidden('user_id', $adminUser->id ) !!}
@endif

<!-- Facebook Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('facebook_url', 'Facebook Url:') !!}
    {!! Form::text('facebook_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-facebook fa-2x form-control-feedback social-link-field-fb"></span>
</div>

<!-- Twitter Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('twitter_url', 'Twitter Url:') !!}
    {!! Form::text('twitter_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-twitter fa-2x form-control-feedback social-link-field-tw"></span>
</div>

<!-- Reddit Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reddit_url', 'Reddit Url:') !!}
    {!! Form::text('reddit_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-reddit fa-2x form-control-feedback social-link-field-rd"></span>
</div>

<!-- Google Plus Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('google_plus_url', 'Google Plus Url:') !!}
    {!! Form::text('google_plus_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-google-plus fa-2x form-control-feedback social-link-field-gplus"></span>
</div>

<!-- Youtube Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('youtube_url', 'Youtube Url:') !!}
    {!! Form::text('youtube_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-youtube fa-2x form-control-feedback social-link-field-yt"></span>
</div>

<!-- Tumblr Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tumblr_url', 'Tumblr Url:') !!}
    {!! Form::text('tumblr_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-tumblr fa-2x form-control-feedback social-link-field-tumblr"></span>
</div>

<!-- Vimeo Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vimeo_url', 'Vimeo Url:') !!}
    {!! Form::text('vimeo_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-vimeo fa-2x form-control-feedback social-link-field-vimeo"></span>
</div>

<!-- Vkontakte Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vkontakte_url', 'Vkontakte Url:') !!}
    {!! Form::text('vkontakte_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-vk fa-2x form-control-feedback social-link-field-vk"></span>
</div>

<!-- Sinaweibo Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sinaweibo_url', 'Sina Weibo Url:') !!}
    {!! Form::text('sinaweibo_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-weibo fa-2x form-control-feedback social-link-field-weibo"></span>
</div>

<!-- Xing Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('xing_url', 'Xing Url:') !!}
    {!! Form::text('xing_url', null, ['class' => 'form-control', 'type' => 'url']) !!}
    <span class="fa fa-xing fa-2x form-control-feedback social-link-field-xing"></span>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('settings') . '#social-links' !!}" class="btn btn-default">Cancel</a>
</div>
