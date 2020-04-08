<!-- Used in updating user. -->
{!! Form::hidden('id', !empty($user) ? $user->id : null) !!}
<!-- User Name Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('user_name') ? ' has-error' : '' }}">
    {!! Form::label('user_name', 'User Name:') !!}
    {!! Form::text(
        'user_name', null,
        ['class' => 'form-control', 'placeholder' => "User Name (e.g. BitcoinIsAwesome)", !empty($user) ? 'readonly' : '']
        )
    !!}
    <span class="glyphicon glyphicon-user form-control-feedback"></span>
    @if ($errors->has('user_name'))
        <span class="help-block">
            <strong>{{ $errors->first('user_name') }}</strong>
        </span>
    @endif
</div>

<!-- First Name Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('first_name') ? ' has-error' : '' }}">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => "First Name (e.g. Satoshi)"]) !!}
    <span class="glyphicon glyphicon-user form-control-feedback"></span>
    @if ($errors->has('first_name'))
        <span class="help-block">
            <strong>{{ $errors->first('first_name') }}</strong>
        </span>
    @endif
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('last_name') ? ' has-error' : '' }}">
    {!! Form::label('last_name', 'Last Name:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => "Last Name (e.g. Nakamoto)"]) !!}
    <span class="glyphicon glyphicon-user form-control-feedback"></span>
    @if ($errors->has('last_name'))
        <span class="help-block">
            <strong>{{ $errors->first('last_name') }}</strong>
        </span>
    @endif
</div>

<!-- Email Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => "Email Address (e.g. satoshi.nakamoto@bitcoin.com)"]) !!}
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
</div>

<!-- Password Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => "Password (e.g. P@ssw0rD)"]) !!}
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    @if ($errors->has('password'))
        <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
    @endif
</div>

<!-- Password Confirm Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
    {!! Form::label('password_confirmation', 'Password:') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => "Password Confirmation (e.g. P@ssw0rD)"]) !!}
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    @if ($errors->has('password_confirmation'))
        <span class="help-block">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
        </span>
    @endif
</div>

<!-- Bitcoin Address Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('bitcoin_address') ? ' has-error' : '' }}">
    {!! Form::label('bitcoin_address', 'Bitcoin Address:') !!}
    {!! Form::text('bitcoin_address', null, ['class' => 'form-control', 'placeholder' => "Valid Bitcoin Address (e.g. 13vYNWKj3npQTYr7EJVBhcoVkwncEbDUvJ)"]) !!}
    <span class="glyphicon glyphicon-bitcoin form-control-feedback"></span>
    @if ($errors->has('bitcoin_address'))
        <span class="help-block">
            <strong>{{ $errors->first('bitcoin_address') }}</strong>
        </span>
    @endif
</div>

<!-- Subscribed Email Field -->
<div class="form-group col-sm-6 has-feedback{{ $errors->has('subscribe_email') ? ' has-error' : '' }}">
    {!! Form::label('subscribe_email', 'Subscribed to emails:') !!}

    {!! Form::select('subscribe_email', [true => 'Yes', false => 'No'], !empty($user) ? intval($user->subscribe_email): null, ['class' => 'form-control']) !!}


    @if ($errors->has('subscribe_email'))
        <span class="help-block">
		    <strong>{{ $errors->first('subscribe_email') }}</strong>
		</span>
    @endif
</div>

<!-- Submit Field -->
<div class="row">
    <div class="form-group col-sm-12" style="margin-left: 1.1em;">
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
    </div>
</div>