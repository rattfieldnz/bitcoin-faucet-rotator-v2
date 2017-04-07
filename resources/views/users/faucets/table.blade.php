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
                @if(Route::currentRouteName() != 'users.faucets.create')
                    <th>Has Been Deleted</th>
                    <th>Action</th>
                @endif
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
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                        @if(Route::getCurrentRoute() != 'users.faucets.create')
                            @if(!empty($faucet) && $faucet->pivot != null)
                                <td>{!! $faucet->pivot->deleted_at != null ? "Yes" : "No" !!}</td>
                                <td>
                                    @if($faucet->pivot->deleted_at != null)
                                        @if(Auth::user()->hasRole('owner') || Auth::user() == $user)
                                            @if(
                                                Auth::user()->hasPermission('permanent-delete-faucets') ||
                                                Auth::user()->hasPermission('permanent-delete-user-faucets')
                                            )
                                                {!! Form::open(['route' => ['users.faucets.delete-permanently', $user->slug, $faucet->slug], 'method' => 'delete']) !!}
                                                {!! csrf_field() !!}
                                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"]) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        @endif
                                        @if(Auth::user()->hasRole('owner') || Auth::user() == $user)
                                            @if(
                                                Auth::user()->hasPermission('restore-faucets') ||
                                                Auth::user()->hasPermission('restore-user-faucets')
                                            )
                                                {!! Form::open(['route' => ['users.faucets.restore', $user->slug, $faucet->slug], 'method' => 'patch']) !!}
                                                {!! csrf_field() !!}
                                                {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted faucet?')"]) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        @endif
                                    @else
                                        @if(Auth::user()->hasRole('owner') || Auth::user() == $user)
                                            @if(
                                                Auth::user()->hasPermission('soft-delete-faucets') ||
                                                Auth::user()->hasPermission('soft-delete-user-faucets')
                                            )
                                                {!! Form::open(['route' => ['users.faucets.destroy', $user->slug, $faucet->slug], 'method' => 'delete']) !!}
                                                {!! csrf_field() !!}
                                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                                {!! Form::close() !!}
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
    </tbody>
</table>
</div>