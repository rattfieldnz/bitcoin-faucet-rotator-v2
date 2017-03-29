@if(Auth::user() != null)
    @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
        <!-- Id Field -->
        <div class="form-group">
            {!! Form::label('id', 'Id:') !!}
            <p>{!! $faucet->id !!}</p>
        </div>
    @endif
@endif

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $faucet->name !!}</p>
</div>

<!-- Url Field -->
<div class="form-group">
    {!! Form::label('url', 'Url:') !!}
    <p>{!! $faucet->url !!}</p>
</div>

<!-- Interval Minutes Field -->
<div class="form-group">
    {!! Form::label('interval_minutes', 'Interval Minutes:') !!}
    <p>{!! $faucet->interval_minutes !!}</p>
</div>

<!-- Min Payout Field -->
<div class="form-group">
    {!! Form::label('min_payout', 'Min Payout:') !!}
    <p>{!! $faucet->min_payout !!}</p>
</div>

<!-- Max Payout Field -->
<div class="form-group">
    {!! Form::label('max_payout', 'Max Payout:') !!}
    <p>{!! $faucet->max_payout !!}</p>
</div>

<!-- Has Ref Program Field -->
<div class="form-group">
    {!! Form::label('has_ref_program', 'Has Ref Program:') !!}
    <p>{!! $faucet->has_ref_program == true ? "Yes" : "No" !!}</p>
</div>

<!-- Ref Payout Percent Field -->
<div class="form-group">
    {!! Form::label('ref_payout_percent', 'Ref Payout Percent:') !!}
    <p>{!! $faucet->ref_payout_percent !!}</p>
</div>

<!-- Comments Field -->
<div class="form-group">
    {!! Form::label('comments', 'Comments:') !!}
    <p>{!! $faucet->comments == null ? "None" : $faucet->comments !!}</p>
</div>

<!-- Is Paused Field -->
<div class="form-group">
    {!! Form::label('is_paused', 'Is Paused:') !!}
    <p>{!! $faucet->is_paused == true ? "Yes" : "No" !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $faucet->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $faucet->updated_at !!}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{!! $faucet->slug !!}</p>
</div>

<!-- Meta Title Field -->
<div class="form-group">
    {!! Form::label('meta_title', 'Meta Title:') !!}
    <p>{!! $faucet->meta_title !!}</p>
</div>

<!-- Meta Description Field -->
<div class="form-group">
    {!! Form::label('meta_description', 'Meta Description:') !!}
    <p>{!! $faucet->meta_description !!}</p>
</div>

<!-- Meta Keywords Field -->
<div class="form-group">
    {!! Form::label('meta_keywords', 'Meta Keywords:') !!}
    <p>{!! $faucet->meta_keywords !!}</p>
</div>

<!-- Has Low Balance Field -->
<div class="form-group">
    {!! Form::label('has_low_balance', 'Has Low Balance:') !!}
    <p>{!! $faucet->has_low_balance == true ? "Yes" : "No" !!}</p>
</div>

