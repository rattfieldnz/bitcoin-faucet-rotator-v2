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
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                @if(Route::currentRouteName() != 'users.faucets.create')
                    <th>Deleted?</th>
                @endif
                <th>Action</th>
            @endif
        @endif
        </thead>
        <tbody>
        @if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
            {!! Form::open(['route' => ['users.faucets.update-multiple', $user->slug], 'method' => 'POST', 'class' => 'form-inline']) !!}
            {!! Form::hidden('_method', 'PATCH') !!}
            {!! Form::hidden('user_id', $user->id) !!}
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
                @if(Auth::user() != null)
                    @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                        @if(Route::getCurrentRoute() != 'users.faucets.create' || Route::getCurrentRoute() == 'users.faucets')
                            @if(!empty($faucet) && $faucet->pivot != null)
                                <td>{!! $faucet->pivot->deleted_at != null ? "Yes" : "No" !!}</td>
                                <td>
                                    @if($faucet->pivot->deleted_at != null)
                                        @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                                            @if(
                                                Auth::user()->hasPermission('permanent-delete-faucets') ||
                                                $user->hasPermission('permanent-delete-user-faucets')
                                            )
                                                {!! link_to_route(
                                                    'users.faucets.delete-permanently',
                                                    '',
                                                    [
                                                        'userSlug' => $user->slug,
                                                        'faucetSlug' => $faucet->slug,
                                                        'payment-processor' => $paymentProcessor->slug
                                                    ],
                                                    [
                                                        'class' => 'btn btn-danger btn-xs glyphicon glyphicon-trash',
                                                        'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"
                                                    ]
                                                    )
                                                !!}
                                            @endif
                                        @endif
                                        @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                                            @if(
                                                Auth::user()->hasPermission('restore-faucets') ||
                                                Auth::user()->hasPermission('restore-user-faucets')
                                            )
                                                {!! link_to_route(
                                                    'users.faucets.restore',
                                                    '',
                                                    [
                                                        'userSlug' => $user->slug,
                                                        'faucetSlug' => $faucet->slug,
                                                        'payment-processor' => $paymentProcessor->slug
                                                    ],
                                                    [
                                                        'class' => 'btn btn-info btn-xs glyphicon glyphicon-refresh',
                                                        'onclick' => "return confirm('Are you sure you want to restore this deleted faucet?')"
                                                    ]
                                                    )
                                                !!}
                                            @endif
                                        @endif
                                    @else
                                        @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                                            @if(
                                                Auth::user()->hasPermission('soft-delete-faucets') ||
                                                Auth::user()->hasPermission('soft-delete-user-faucets')
                                            )
                                                {!! link_to_route(
                                                    'users.faucets.destroy',
                                                    '',
                                                    [
                                                        'userSlug' => $user->slug,
                                                        'faucetSlug' => $faucet->slug,
                                                        'payment-processor' => $paymentProcessor->slug
                                                    ],
                                                    [
                                                        'class' => 'btn btn-warning btn-xs glyphicon glyphicon-trash',
                                                        'onclick' => "return confirm('Are you sure you want to archive/delete this faucet?')"
                                                    ]
                                                    )
                                                !!}
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endif
                @endif
            </tr>
        @endforeach
        @if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
            {!! Form::submit('Save Referral Codes', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        @endif
        </tbody>
    </table>
</div>