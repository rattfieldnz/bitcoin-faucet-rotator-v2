<div class="table-responsive" style="margin:0.5em !important;">
    <table class="table table-striped bordered tablesorter" id="faucets-table">
        <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                <th>Referral Code</th>
            @endif
        @endif
        <th>Interval Minutes</th>
        <th>Min. Payout</th>
        <th>Max. Payout</th>
        <th>Payment Processors</th>
        </thead>
        <tbody>
        @if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
            {!! Form::open(['route' => ['users.faucets.update-multiple', $user->slug], 'method' => 'POST', 'class' => 'form-inline']) !!}
            {!! Form::hidden('_method', 'PATCH') !!}
            {!! Form::hidden('user_id', $user->id) !!}
            {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
            {!! Form::hidden('current_route_name', Route::currentRouteName())!!}
        @endif
        @foreach($faucets as $faucet)
            <tr>
                @if(Auth::user() != null)
                    @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                        <td>{!! $faucet->id !!}</td>
                    @endif
                @endif
                <td>{!! link_to(route('users.faucets.show', [$user->slug, $faucet->slug]), $faucet->name, ['target' => 'blank', 'title' => $faucet->name]) !!}</td>

                @if(Auth::user() != null)
                    @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                        @if(!empty($faucet))
                            <td>
                                {!! Form::hidden('faucet_id[]', $faucet->id) !!}
                                {!! Form::text('referral_code[]', \App\Helpers\Functions\Faucets::getUserFaucetRefCode($user, $faucet), ['class' => 'form-control', 'placeholder' => 'ABCDEF123456']) !!}
                            </td>
                        @endif
                    @endif
                @endif
                <td>{!! $faucet->interval_minutes !!}</td>
                <td>{!! $faucet->min_payout !!}</td>
                <td>{!! $faucet->max_payout !!}</td>
                <td>
                    <ul>
                        @foreach($faucet->paymentProcessors as $p)
                            <li>{!! link_to(route('payment-processors.show', $p->slug), $p->name, ['target' => 'blank', 'title' => $p->name]) !!}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        @endforeach
        @if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
            {!! Form::button(
                '<i class="fa fa-floppy-o" style="vertical-align: middle; margin-right:0.25em;"></i>Save Referral Codes',
                [
                    'type' => 'submit',
                    'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                    'style' => 'margin:0.25em 0.25em 0 0; color: white; min-width:12em;'
                ])
            !!}
            {!! Form::close() !!}
            {!! Form::button(
                '<i class="fa fa-link" style="vertical-align: middle; margin-right:0.25em;"></i>View ' . $paymentProcessor->name . ' Rotator',
                [
                    'type' => 'button',
                    'onClick' => "window.open('" . route('users.payment-processors.rotator', ['userSlug' => $user->slug, 'paymentProcessorSlug' => $paymentProcessor->slug]) . "', '_blank')",
                    'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                    'style' => 'margin:0.25em 0 0.25em 0.25em; color: white; min-width:12em;'
                ])
            !!}
        @endif
        </tbody>
    </table>
</div>