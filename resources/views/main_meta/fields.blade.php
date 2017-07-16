<!-- Title Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('title') ? ' has-error' : '' }}">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => "This is your meta title, displays in your browser tab."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('title'))
        <span class="help-block">
            <strong>{{ $errors->first('title') }}</strong>
        </span>
    @endif
</div>

<!-- Description Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('description') ? ' has-error' : '' }}">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => "This is your meta description, used for display in search engines."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('description'))
        <span class="help-block">
            <strong>{{ $errors->first('description') }}</strong>
        </span>
    @endif
</div>

<!-- Keywords Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('keywords') ? ' has-error' : '' }}">
    {!! Form::label('keywords', 'Keywords (comma separated):') !!}
    {!! Form::text('keywords', null, ['class' => 'form-control', 'placeholder' => "Keyword 1, Keyword 2, Keyword 3 - used for display in search engines."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('keywords'))
        <span class="help-block">
            <strong>{{ $errors->first('keywords') }}</strong>
        </span>
    @endif
</div>

<!-- Google Analytics Id Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('google_analytics_id') ? ' has-error' : '' }}">
    {!! Form::label('google_analytics_id', 'Google Analytics Id:') !!}
    {!! Form::text('google_analytics_id', null, ['class' => 'form-control', 'placeholder' => "Eg: UA-12345678-9."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('google_analytics_id'))
        <span class="help-block">
            <strong>{{ $errors->first('google_analytics_id') }}</strong>
        </span>
    @endif
</div>

<!-- Yandex Verification Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('yandex_verification') ? ' has-error' : '' }}">
    {!! Form::label('yandex_verification', 'Yandex Verification:') !!}
    {!! Form::text('yandex_verification', null, ['class' => 'form-control', 'placeholder' => "Eg: 5tty74h660phgg7."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('yandex_verification'))
        <span class="help-block">
            <strong>{{ $errors->first('yandex_verification') }}</strong>
        </span>
    @endif
</div>

<!-- Bing Verification Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('bing_verification') ? ' has-error' : '' }}">
    {!! Form::label('bing_verification', 'Bing Verification:') !!}
    {!! Form::text('bing_verification', null, ['class' => 'form-control', 'placeholder' => "Eg: 12DF1DB1C5623G9FG1C342D046F235F2."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('bing_verification'))
        <span class="help-block">
            <strong>{{ $errors->first('bing_verification') }}</strong>
        </span>
    @endif
</div>

<!-- Page Main Title Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('page_main_title') ? ' has-error' : '' }}">
    {!! Form::label('page_main_title', 'Page Main Title:') !!}
    {!! Form::text('page_main_title', null, ['class' => 'form-control', 'placeholder' => "This appears at the top of the main content, on the page itself."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('page_main_title'))
        <span class="help-block">
            <strong>{{ $errors->first('page_main_title') }}</strong>
        </span>
    @endif
</div>

<!-- Page Main Content Field -->
<div class="form-group col-sm-12 col-lg-12 has-feedback{{ $errors->has('page_main_content') ? ' has-error' : '' }}">
    {!! Form::label('page_main_content', 'Page Main Content:') !!}
    {!!
        Form::textarea(
            'page_main_content',
            null,
            [
                'class' => 'form-control',
                'placeholder' =>
                "Tonight on Campbell Live -. We go together, kinda like mince n cheese ya know, this hard yakka kai moana is as primo as a hammered holden. Mean while, in a waka, Manus Morissette and Mrs Falani were up to no good with a bunch of tip-top pavlovas. (http://kiwipsum.com)."
            ]
        )
    !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('page_main_content'))
        <span class="help-block">
            <strong>{{ $errors->first('page_main_content') }}</strong>
        </span>
    @endif
</div>

<!-- Addthisid Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('addthisid') ? ' has-error' : '' }}">
    {!! Form::label('addthisid', 'AddThis Id:') !!}
    {!! Form::text('addthisid', null, ['class' => 'form-control', 'placeholder' => "Eg: sb-61c007c25053c2gc."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('addthisid'))
        <span class="help-block">
            <strong>{{ $errors->first('addthisid') }}</strong>
        </span>
    @endif
</div>

<!-- Twitter Username Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('twitter_username') ? ' has-error' : '' }}">
    {!! Form::label('twitter_username', 'Twitter Username (without "@"):') !!}
    {!! Form::text('twitter_username', null, ['class' => 'form-control', 'placeholder' => "Eg: FreeBTCWebsite."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('twitter_username'))
        <span class="help-block">
            <strong>{{ $errors->first('twitter_username') }}</strong>
        </span>
    @endif
</div>

<!-- Feedburner Feed Url Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('feedburner_feed_url') ? ' has-error' : '' }}">
    {!! Form::label('feedburner_feed_url', 'Feedburner Feed Url:') !!}
    {!! Form::text('feedburner_feed_url', null, ['class' => 'form-control', 'placeholder' => 'E.g http://feeds.feedburner.com/freebtcwebsitefeed.']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('feedburner_feed_url'))
        <span class="help-block">
            <strong>{{ $errors->first('feedburner_feed_url') }}</strong>
        </span>
    @endif
</div>

<!-- Disqus Shortname Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('disqus_shortname') ? ' has-error' : '' }}">
    {!! Form::label('disqus_shortname', 'Disqus Shortname:') !!}
    {!! Form::text('disqus_shortname', null, ['class' => 'form-control', 'placeholder' => 'E.g your-disqus-shortname.']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('disqus_shortname'))
        <span class="help-block">
            <strong>{{ $errors->first('disqus_shortname') }}</strong>
        </span>
    @endif
</div>

<!-- Prevent Adblock Blocking Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('prevent_adblock_blocking') ? ' has-error' : '' }}">
    {!! Form::label('prevent_adblock_blocking', 'Prevent Adblock Blocking:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('prevent_adblock_blocking', false) !!}
        {!! Form::checkbox('prevent_adblock_blocking', '1', null) !!} 1
    </label>
    @if ($errors->has('prevent_adblock_blocking'))
        <span class="help-block">
            <strong>{{ $errors->first('prevent_adblock_blocking') }}</strong>
        </span>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('main-meta.index') !!}" class="btn btn-default">Cancel</a>
</div>

@section('scripts')
    <script src="/assets/js/ckeditor/ckeditor.js?{{ rand()}}"></script>
    <script>
        CKEDITOR.replace( 'page_main_content');
    </script>
@endsection