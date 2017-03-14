<!-- Name Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => "An Awesome Payment Processor"]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('name'))
        <span class="help-block">
		    <strong>{{ $errors->first('name') }}</strong>
		</span>
    @endif
</div>

<!-- Url Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('url') ? ' has-error' : '' }}">
    {!! Form::label('url', 'Url:') !!}
    {!! Form::text('url', null, ['class' => 'form-control', 'placeholder' => "https://www.anawesomepaymentprocessor.com"]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('url'))
        <span class="help-block">
		    <strong>{{ $errors->first('url') }}</strong>
		</span>
    @endif
</div>

<!-- Meta Title Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('meta_title') ? ' has-error' : '' }}">
    {!! Form::label('meta_title', 'Meta Title:') !!}
    {!! Form::text('meta_title', null, ['class' => 'form-control', 'placeholder' => "The Greatest Payment Processor of All Time."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('meta_title'))
        <span class="help-block">
		    <strong>{{ $errors->first('meta_title') }}</strong>
		</span>
    @endif
</div>

<!-- Meta Description Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('meta_description') ? ' has-error' : '' }}">
    {!! Form::label('meta_description', 'Meta Description:') !!}
    {!! Form::text('meta_description', null, ['class' => 'form-control', 'placeholder' => "This is the greatest payment processor of all time."]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('meta_description'))
        <span class="help-block">
		    <strong>{{ $errors->first('meta_description') }}</strong>
		</span>
    @endif
</div>

<!-- Meta Keywords Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('meta_keywords') ? ' has-error' : '' }}">
    {!! Form::label('meta_keywords', 'Meta Keywords (comma separated):') !!}
    {!! Form::text('meta_keywords', null, ['class' => 'form-control', 'placeholder' => "Awesome Payment Processor, Crypto, Bitcoin"]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('meta_keywords'))
        <span class="help-block">
		    <strong>{{ $errors->first('meta_keywords') }}</strong>
		</span>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('payment-processors.index') !!}" class="btn btn-default">Cancel</a>
</div>
