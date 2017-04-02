<div class="table-responsive" style="margin:0.5em !important;">
<table class="table table-striped bordered tablesorter" id="faucets-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                <th>Referral Code</th>
            @endif
        @endif
        <th>Interval Minutes</th>
        <th>Min. Payout</th>
        <th>Max. Payout</th>
        <th>Payment Processors</th>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                <th>Has Been Deleted</th>
            @endif
        @endif
    </thead>
    <tbody>
    @foreach($faucets as $faucet)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                    <td>{!! $faucet->id !!}</td>
                @endif
            @endif
            <td>{!! link_to(route('users.faucets.show', [$user->slug, $faucet->slug]), $faucet->name, ['target' => 'blank', 'title' => $faucet->name]) !!}</td>

            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                    <td>
                        @if($faucet->pivot == null)
                            {!! Form::open(['route' => ['users.faucets.store', $user->slug], 'method' => 'POST', 'class' => 'form-inline']) !!}
                        @else
                            {!! Form::open(['route' => ['users.faucets.update', $user->slug, $faucet->slug], 'method' => 'POST', 'class' => 'form-inline']) !!}
                            {!! Form::hidden('_method', 'PATCH') !!}
                        @endif
                        {!! Form::hidden('user_id', $user->id) !!}
                        {!! Form::hidden('faucet_id', $faucet->id) !!}
                        {!! Form::text('referral_code', \App\Helpers\Functions::getUserFaucetRefCode($user, $faucet), ['class' => 'form-control', 'placeholder' => 'ABCDEF123456']) !!}
                        {!! Form::submit('Save Ref Code', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </td>
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
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                    <td>{!! $faucet->isDeleted() == true ? "Yes" : "No" !!}</td>
                @endif
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</div>