<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>InfyOm Laravel Generator | Registration Page</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/AdminLTE.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="{{ url('/home') }}"><b>InfyOm </b>Generator</a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>

        <form method="post" action="{{ url('/register') }}">

            {!! csrf_field() !!}
            {!! Form::hidden('is_admin', 0) !!}

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
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => "P@ssw0rD"]) !!}
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
                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => "P@ssw0rD"]) !!}
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

                <!-- Submit Field -->
                <div class="row">
                    <div class="col-xs-8">
                        <p>By registering, I fully agree to the <a href="#">terms and conditions</a>.</p>
                    </div>
                    <div class="col-xs-4">
                        {!! Form::submit('Register', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
        </form>

        <a href="{{ url('/login') }}" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/js/app.min.js"></script>

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
