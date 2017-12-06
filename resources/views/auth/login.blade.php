<!DOCTYPE html>
<html lang="{{ \App\Models\MainMeta::first()->language()->first()->isoCode() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Bitcoin Faucet Rotator Login Page</title>
    @if(env('APP_ENV') == 'local')
        <link rel="stylesheet" href="{{ asset("/assets/css/mainStyles.css?". rand()) }}">
    @elseif(env('APP_ENV') == 'production')
        <link rel="stylesheet" href="{{ asset("/assets/css/mainStyles.min.css?". rand()) }}">
    @endif

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body id="guest-bg" class="hold-transition login-page">
@include('layouts.partials.navigation._guest_navigation')
<div class="login-box">
    <div class="login-logo">
        <strong><i class="fa fa-2x fa-bitcoin"></i>itcoin Faucet Rotator</strong>
        @include('layouts.partials.social.addthis')
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="post" action="{{ url('/login') }}">
            {!! csrf_field() !!}

            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
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

            <div class="row">
                <div class="col-xs-8">
                    <div id="remember-me-checkbox" class="checkbox icheck">
                        <label for="checkbox" id="checkbox-label">Remember Me</label>
                        <input id="checkbox" type="checkbox" name="remember">
                    </div>

                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <p><a href="{{ url('/password/reset') }}">I forgot my password</a>.<br>
           <a href="{{ url('/register') }}" class="text-center">Register a new membership</a>.</p>
        <p>By logging in, you accept the {!! link_to_route('privacy-policy', "Privacy Policy") !!} and {!! link_to_route('terms-and-conditions', "Terms &amp; Conditions") !!}.</p>
    </div>
    <!-- /.login-box-body -->
</div>
@include('layouts.partials.content._footer')
<!-- /.login-box -->
@include('layouts.partials.scripts._js')
<script>
    $(function () {
        $('#remember-me-checkbox input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
