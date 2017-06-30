<div class="table-responsive">
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
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                <th>Has Been Deleted</th>
            @endif
        @endif
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                <th>Action</th>
            @endif
        @endif
    </thead>
    <tbody>
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
                            @if($faucet->pivot == null)
                                {!! Form::open(['route' => ['users.faucets.store', $user->slug], 'method' => 'POST', 'class' => 'form-inline']) !!}
                            @else
                                {!! Form::open(['route' => ['users.faucets.update', $user->slug, $faucet->slug], 'method' => 'POST', 'class' => 'form-inline']) !!}
                                {!! Form::hidden('_method', 'PATCH') !!}
                            @endif
                            {!! Form::hidden('user_id', $user->id) !!}
                            {!! Form::hidden('faucet_id', $faucet->id) !!}
                            @if(!empty($paymentProcessor))
                                {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                            @endif
                            {!! Form::text('referral_code', \App\Helpers\Functions\Faucets::getUserFaucetRefCode($user, $faucet), ['class' => 'form-control', 'placeholder' => 'ABCDEF123456']) !!}
                            {!! Form::submit('Save Ref Code', ['class' => 'btn btn-primary']) !!}
                            {!! Form::close() !!}
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
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                    <td>{!! $faucet->pivot->deleted_at != null ? "Yes" : "No" !!}</td>
                @endif
            @endif

            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
            <td>
                <div class='btn-group'>
                            @if($faucet->pivot->deleted_at != null)

                                {!! Form::open(['route' => ['users.faucets.delete-permanently', $user->userSlug(), $faucet->slug], 'method' => 'delete']) !!}
                                @if(!empty($paymentProcessor))
                                    {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                                @endif
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"]) !!}
                                {!! Form::close() !!}

                                {!! Form::open(['route' => ['users.faucets.restore', $user->userSlug(), $faucet->slug], 'method' => 'patch']) !!}
                                @if(!empty($paymentProcessor))
                                    {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                                @endif
                                {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted faucet?')"]) !!}
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['route' => ['users.faucets.destroy', $user->userSlug(), $faucet->slug], 'method' => 'delete']) !!}
                                @if(!empty($paymentProcessor))
                                    {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                                @endif
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                {!! Form::close() !!}
                            @endif
                        @endif
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>