{!! Form::hidden('id', !empty($faucet) ? $faucet->id : null) !!}
<!-- Name Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'An Awesome Faucet']) !!}
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
    {!! Form::text('url', null, ['class' => 'form-control', 'placeholder' => 'https://www.anawesomefaucet.com']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('url'))
        <span class="help-block">
		    <strong>{{ $errors->first('url') }}</strong>
		</span>
    @endif
</div>

<!-- Referral Code Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('referral_code') ? ' has-error' : '' }}">
	{!! Form::label('referral_code', 'Referral Code:') !!}
	{!! Form::text('referral_code', !empty($faucet) ? $faucet->users()->first()->pivot->referral_code : null, ['class' => 'form-control', 'placeholder' => 'ABCDEF123456']) !!}
	<span class="glyphicon glyphicon-pencil form-control-feedback"></span>
	@if ($errors->has('referral_code'))
		<span class="help-block">
		    <strong>{{ $errors->first('referral_code') }}</strong>
		</span>
	@endif
</div>

<!-- Minutes Between Claims Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('interval_minutes') ? ' has-error' : '' }}">
    {!! Form::label('interval_minutes', 'Minutes Between Claims:') !!}
    {!! Form::number('interval_minutes', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
    <span class="glyphicon glyphicon-time form-control-feedback"></span>
    @if ($errors->has('interval_minutes'))
        <span class="help-block">
		    <strong>{{ $errors->first('interval_minutes') }}</strong>
		</span>
    @endif
</div>

<!-- Min Payout Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('min_payout') ? ' has-error' : '' }}">
    {!! Form::label('min_payout', 'Min Payout:') !!}
    {!! Form::number('min_payout', null, ['class' => 'form-control', 'placeholder' => '1']) !!}
    <span class="glyphicon glyphicon-bitcoin form-control-feedback"></span>
    @if ($errors->has('min_payout'))
        <span class="help-block">
		    <strong>{{ $errors->first('min_payout') }}</strong>
		</span>
    @endif
</div>

<!-- Max Payout Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('min_payout') ? ' has-error' : '' }}">
    {!! Form::label('max_payout', 'Max Payout:') !!}
    {!! Form::number('max_payout', null, ['class' => 'form-control', 'placeholder' => '100']) !!}
    <span class="glyphicon glyphicon-bitcoin form-control-feedback"></span>
    @if ($errors->has('max_payout'))
        <span class="help-block">
		    <strong>{{ $errors->first('max_payout') }}</strong>
		</span>
    @endif
</div>

<!-- Has Ref Program Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('has_ref_program') ? ' has-error' : '' }}">
    {!! Form::label('has_ref_program', 'Has Referral Program:') !!}

    {!! Form::label('has_ref_program', 'Yes') !!}    {!! Form::radio('has_ref_program', 1) !!}
    {!! Form::label('has_ref_program', 'No') !!}    {!! Form::radio('has_ref_program', 0) !!}

    @if ($errors->has('has_ref_program'))
        <span class="help-block">
		    <strong>{{ $errors->first('has_ref_program') }}</strong>
		</span>
    @endif
</div>

<!-- Ref Payout Percent Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('ref_payout_percent') ? ' has-error' : '' }}">
    {!! Form::label('ref_payout_percent', 'Referral Payout Percent:') !!}
    {!! Form::number('ref_payout_percent', null, ['class' => 'form-control','placeholder' => '100']) !!}
    <span class="glyphicon glyphicon-bitcoin form-control-feedback"></span>
    @if ($errors->has('ref_payout_percent'))
        <span class="help-block">
		    <strong>{{ $errors->first('ref_payout_percent') }}</strong>
		</span>
    @endif
</div>


<div class="form-group col-sm-6 has-feedback{{ $errors->has('payment_processors') ? ' has-error' : '' }}">
    {!! Form::label('payment_processors[]', 'Payment Processors:') !!}<br />
    {!! Form::select('payment_processors[]', $paymentProcessors->pluck('name', 'id'),
        $faucetPaymentProcessorIds,
        ['class' => 'form-control',
        'multiple' => 'multiple'])
    !!}
    @if ($errors->has('payment_processors'))
        <span class="help-block">
		    <strong>{{ $errors->first('payment_processors') }}</strong>
		</span>
    @endif
</div>

<!-- Comments Field -->
<div class="form-group col-sm-12 col-lg-12 has-feedback{{ $errors->has('comments') ? ' has-error' : '' }}">
    {!! Form::label('comments', 'Comments:') !!}
    {!! Form::text('comments', null, ['class' => 'form-control', 'placeholder' => 'Sup bro! Got a dollar for the bus?, this cool pukeko is as outrageously awesome as a good as hongi. Can\'t handle the jandle. Mean while, in a waka, Maui and Bazza were up to no good with a bunch of stuffed vivids. (http://kiwipsum.com)']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('comments'))
        <span class="help-block">
		    <strong>{{ $errors->first('comments') }}</strong>
		</span>
    @endif
</div>

<!-- Is Paused Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('is_paused') ? ' has-error' : '' }}">
    {!! Form::label('is_paused', 'Is Paused:') !!}

    {!! Form::label('is_paused', 'Yes') !!}    {!! Form::radio('is_paused', 1) !!}
    {!! Form::label('is_paused', 'No') !!}    {!! Form::radio('is_paused', 0) !!}

    @if ($errors->has('is_paused'))
        <span class="help-block">
		    <strong>{{ $errors->first('is_paused') }}</strong>
		</span>
    @endif
</div>

<!-- Meta Title Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('meta_title') ? ' has-error' : '' }}">
    {!! Form::label('meta_title', 'Meta Title:') !!}
    {!! Form::text('meta_title', null, ['class' => 'form-control', 'placeholder' => 'Sup bro! Got a dollar for the bus?']) !!}
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
    {!! Form::text('meta_description', null, ['class' => 'form-control', 'placeholder' => 'Sup bro! Got a dollar for the bus?']) !!}
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
    {!! Form::text('meta_keywords', null, ['class' => 'form-control', 'placeholder' => 'Keyword 1, Keyword 2, Keyword 3, Keyword 4, Keyword 5']) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('meta_keywords'))
        <span class="help-block">
		    <strong>{{ $errors->first('meta_keywords') }}</strong>
		</span>
    @endif
</div>

<!-- Twitter Message Field -->
<div class="form-group col-sm-10 has-feedback{{ $errors->has('twitter_message') ? ' has-error' : '' }}">
	{!! Form::label('twitter_message', 'Twitter Message*:') !!}
	{!! Form::text(
	    'twitter_message', null,
	    [
	        'class' => 'form-control',
	        'placeholder' => 'Earn between [faucet_min_payout] and [faucet_max_payout] satoshis every [faucet_interval] minute/s from [faucet_url] for free :) #FreeBitcoin #Bitcoin'
	    ])
	!!}
	<p><small>*The following placeholders are available: [faucet_name], [faucet_url], [faucet_min_payout], [faucet_max_payout], [faucet_interval].</small></p>

	<span class="glyphicon glyphicon-pencil form-control-feedback"></span>
	@if ($errors->has('twitter_message'))
		<span class="help-block">
		    <strong>{{ $errors->first('twitter_message') }}</strong>
		</span>
	@endif
</div>

<div class="form-group col-sm-2">
	{!! Form::label('send_tweet', 'Send Tweet Notification?' ) !!}
	{!! Form::select('send_tweet', [true => 'Yes', false => 'No'], 0, ['class' => 'form-control']) !!}
</div>

<!-- Has Low Balance Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('has_low_balance') ? ' has-error' : '' }}">
    {!! Form::label('has_low_balance', 'Has Low Balance:') !!}

    {!! Form::label('has_low_balance', 'Yes') !!}    {!! Form::radio('has_low_balance', 1) !!}
    {!! Form::label('has_low_balance', 'No') !!}    {!! Form::radio('has_low_balance', 0) !!}

    @if ($errors->has('has_low_balance'))
        <span class="help-block">
		    <strong>{{ $errors->first('has_low_balance') }}</strong>
		</span>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('faucets.index') !!}" class="btn btn-default">Cancel</a>
</div>
