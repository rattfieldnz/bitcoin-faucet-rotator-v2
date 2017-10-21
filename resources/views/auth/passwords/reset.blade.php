@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="login-box">
            <div class="login-logo">
                <strong><i class="fa fa-2x fa-bitcoin"></i>itcoin Faucet Rotator</strong>
            </div>

            <!-- /.login-logo -->
            <div class="login-box-body">
                <strong><p class="login-box-msg">Reset your password</p></strong>

                <form method="post" action="{{ url('/password/reset') }}">
                    {!! csrf_field() !!}

                    <input type="hidden" name="token" value="{{ $token }}">

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
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        @if ($errors->has('password'))
                            <span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
                        @endif
                    </div>

                    <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
								<strong>{{ $errors->first('password_confirmation') }}</strong>
							</span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-btn fa-refresh"></i>Reset Password
                            </button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- /.login-box-body -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
@endpush

@push('google-analytics')
@include('layouts.partials.tracking._google_analytics')
@endpush