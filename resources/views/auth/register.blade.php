<!DOCTYPE html>
<html lang="{{ \App\Models\MainMeta::first()->language()->first()->isoCode() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Bitcoin Faucet Rotator Registration Page</title>
    @if(env('APP_ENV') == 'local')
        <link rel="stylesheet" href="{{ asset("/assets/css/mainStyles.css?". rand()) }}">
    @else
        <link rel="stylesheet" href="{{ asset("/assets/css/mainStyles.min.css?". rand()) }}">
    @endif

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="guest-bg" class="hold-transition register-page">
@include('layouts.partials.navigation._guest_navigation')
<div class="register-box">
    <div class="register-logo">
        <strong><i class="fa fa-2x fa-bitcoin"></i>itcoin Faucet Rotator</strong>
        @include('layouts.partials.social.addthis')
    </div>
    @include('layouts.partials.site_wide_alerts._alert-content')
    <div class="register-box-body">
        <p class="login-box-msg"><strong>Register a new membership</strong></p>
        <form method="post" action="{{ url('/register') }}">
            {!! csrf_field() !!}
            <!-- User Name Field -->
                <div class="form-group has-feedback{{ $errors->has('user_name') ? ' has-error' : '' }}">
                    {!! Form::label('user_name', 'User Name:') !!}
                    {!! Form::text('user_name', null, ['class' => 'form-control', 'placeholder' => "BitcoinIsAwesome"]) !!}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('user_name'))
                        <span class="help-block">
							<strong>{{ $errors->first('user_name') }}</strong>
						</span>
                    @endif
                </div>

                <!-- First Name Field -->
                <div class="form-group has-feedback{{ $errors->has('first_name') ? ' has-error' : '' }}">
                    {!! Form::label('first_name', 'First Name:') !!}
                    {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => "Satoshi"]) !!}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('first_name'))
                        <span class="help-block">
							<strong>{{ $errors->first('first_name') }}</strong>
						</span>
                    @endif
                </div>

                <!-- Last Name Field -->
                <div class="form-group has-feedback{{ $errors->has('last_name') ? ' has-error' : '' }}">
                    {!! Form::label('last_name', 'Last Name:') !!}
                    {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => "Nakamoto"]) !!}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('last_name'))
                        <span class="help-block">
							<strong>{{ $errors->first('last_name') }}</strong>
						</span>
                    @endif
                </div>

                <!-- Email Field -->
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    {!! Form::label('email', 'Email:') !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => "satoshi.nakamoto@bitcoin.com"]) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
                    @endif
                </div>

                <!-- Password Field -->
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    {!! Form::label('password', 'Password:') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
                    @endif
                </div>

                <!-- Password Confirm Field -->
                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    {!! Form::label('password_confirmation', 'Password Confirmation:') !!}
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
							<strong>{{ $errors->first('password_confirmation') }}</strong>
						</span>
                    @endif
                </div>

                <!-- Bitcoin Address Field -->
                <div class="form-group has-feedback{{ $errors->has('bitcoin_address') ? ' has-error' : '' }}">
                    {!! Form::label('bitcoin_address', 'Bitcoin Address:') !!}
                    {!! Form::text('bitcoin_address', null, ['class' => 'form-control', 'placeholder' => "13vYNWKj3npQTYr7EJVBhcoVkwncEbDUvJ"]) !!}
                    <span class="glyphicon glyphicon-bitcoin form-control-feedback"></span>
                    @if ($errors->has('bitcoin_address'))
                        <span class="help-block">
							<strong>{{ $errors->first('bitcoin_address') }}</strong>
						</span>
                    @endif
                </div>

                <div class="form-group has-feedback{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                    {!! Recaptcha::render([ 'lang' => \App\Models\MainMeta::first()->language()->first()->isoCode() ]) !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="help-block">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>

                <!-- Submit Field -->
                <div class="row">
                    <div class="col-xs-8">
                        <p>By registering, you accept the {!! link_to_route('privacy-policy', "Privacy Policy") !!} and {!! link_to_route('terms-and-conditions', "Terms &amp; Conditions") !!}.</p>
                    </div>
                    <div class="col-xs-4">
                        {!! Form::submit('Register', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
        </form>

        <p><a href="{{ url('/login') }}" class="text-center">I already have an account</a>.</p>

    </div>
    <!-- /.form-box -->
</div>
@include('layouts.partials.content._footer')
<!-- /.register-box -->
@include('layouts.partials.scripts._js')

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
