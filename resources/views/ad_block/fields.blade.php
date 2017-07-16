<!-- Ad Content Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('ad_content', 'Ad Content:') !!}
    {!! Form::textarea('ad_content', null, ['class' => 'form-control']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('ad_content'))
        <span class="help-block">
            <strong>{{ $errors->first('ad_content') }}</strong>
        </span>
    @endif
</div>

<!-- User Id Field -->
{!! Form::hidden('user_id', $adminUser != null ? $adminUser->id : null) !!}

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('ad-block.index') !!}" class="btn btn-default">Cancel</a>
</div>
@section('scripts')
<script src="/assets/js/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'ad_content');
</script>
@endsection