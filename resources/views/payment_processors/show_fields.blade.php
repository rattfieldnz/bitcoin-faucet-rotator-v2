<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $paymentProcessor->name !!}</p>
</div>

<!-- Url Field -->
<div class="form-group">
    {!! Form::label('url', 'Url:') !!}
    <p>{!! $paymentProcessor->url !!}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{!! $paymentProcessor->slug !!}</p>
</div>


@if(Auth::user() != null)
    @if(Auth::user()->is_admin == true)
        <!-- Created At Field -->
        <div class="form-group">
            {!! Form::label('created_at', 'Created At:') !!}
            <p>{!! $paymentProcessor->created_at !!}</p>
        </div>

        <!-- Updated At Field -->
        <div class="form-group">
            {!! Form::label('updated_at', 'Updated At:') !!}
            <p>{!! $paymentProcessor->updated_at !!}</p>
        </div>
    @endif
@endif

<!-- Meta Title Field -->
<div class="form-group">
    {!! Form::label('meta_title', 'Meta Title:') !!}
    <p>{!! $paymentProcessor->meta_title !!}</p>
</div>

<!-- Meta Description Field -->
<div class="form-group">
    {!! Form::label('meta_description', 'Meta Description:') !!}
    <p>{!! $paymentProcessor->meta_description !!}</p>
</div>

<!-- Meta Keywords Field -->
<div class="form-group">
    {!! Form::label('meta_keywords', 'Meta Keywords:') !!}
    <p>{!! $paymentProcessor->meta_keywords !!}</p>
</div>

