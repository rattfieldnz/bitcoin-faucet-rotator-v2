<!-- Title Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('title') ? ' has-error' : '' }}">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => "This is your meta and page title. Displays in browser tab and header."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('title'))
        <span class="help-block">
            <strong>{{ $errors->first('title') }}</strong>
        </span>
    @endif
</div>

<!-- Short Description Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('short_description') ? ' has-error' : '' }}">
    {!! Form::label('short_description', 'Short Description:') !!}
    {!! Form::text('short_description', null, ['class' => 'form-control', 'placeholder' => 'This is used for meta tag data.']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('short_description'))
        <span class="help-block">
            <strong>{{ $errors->first('short_description') }}</strong>
        </span>
    @endif
</div>

<!-- Content Field -->
<div class="form-group col-sm-12 col-lg-12 has-feedback{{ $errors->has('content') ? ' has-error' : '' }}">
    {!! Form::label('content', 'Privacy Policy Content:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'This is where your privacy policy main content goes.', 'id' => 'main_content']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('content'))
        <span class="help-block">
            <strong>{{ $errors->first('content') }}</strong>
        </span>
    @endif
</div>

<!-- Keywords Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('keywords') ? ' has-error' : '' }}">
    {!! Form::label('keywords', 'Keywords:') !!}
    {!! Form::text('keywords', null, ['class' => 'form-control', 'placeholder' => 'Enter your keywords here, separated by commas. Used for meta tag data.']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('keywords'))
        <span class="help-block">
            <strong>{{ $errors->first('keywords') }}</strong>
        </span>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('privacy-policy') !!}" class="btn btn-default">Cancel</a>
</div>



@push('scripts')
<script src="/assets/js/ckeditor/ckeditor.js?{{ rand()}}"></script>
<script>
    CKEDITOR.config.extraAllowedContent = '*(*);*{*}';
    CKEDITOR.replace('main_content');
</script>
@endpush