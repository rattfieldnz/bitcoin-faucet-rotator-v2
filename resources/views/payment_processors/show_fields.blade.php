@if(Auth::user() != null)
    @if(Auth::user()->is_admin == true)
        <!-- Id Field -->
        <div class="form-group">
            {!! Form::label('id', 'Id:') !!}
            <p>{!! $paymentProcessor->id !!}</p>
        </div>
    @endif
@endif

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

@if(count($paymentProcessor->faucets()->get()) > 0)
<div class="form-group">
    {!! Form::label('faucets', 'Faucets:') !!}
    {!! link_to_route('payment-processors.faucets', 'View Faucets', $paymentProcessor->slug) !!}
</div>
@endif

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

