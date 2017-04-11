<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="faucets-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        <th>Url</th>
        <th>Interval Minutes</th>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                <th>Has Been Deleted</th>
            @endif
        @endif
        <th>Action</th>
    </thead>
    <tbody>
    @foreach($faucets as $faucet)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                    <td>{!! $faucet->id !!}</td>
                @endif
            @endif
            <td>{!! $faucet->name !!}</td>
            <td>{!! $faucet->url . $faucet->pivot->referral_code !!}</td>
            <td>{!! $faucet->interval_minutes !!}</td>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                    <td>{!! $faucet->isDeleted() == true ? "Yes" : "No" !!}</td>
                @endif
            @endif
            <td>
                <div class='btn-group'>
                    <a href="{!! route('users.faucets.show', ['userSlug' => $user->slug,'faucetSlug' => $faucet->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @if(Auth::user() != null)
                        @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner') || Auth::user() == $user)
                            @if($faucet->pivot->deleted_at != null)
                                {!! Form::open(['route' => ['users.faucets.delete-permanently', $user->slug, $faucet->slug], 'method' => 'delete']) !!}
                                @if(!empty($paymentProcessor))
                                    {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                                @endif
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"]) !!}
                                {!! Form::close() !!}
                                {!! Form::open(['route' => ['users.faucets.restore', $user->slug, $faucet->slug], 'method' => 'patch']) !!}
                                @if(!empty($paymentProcessor))
                                    {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                                @endif
                                {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted faucet?')"]) !!}
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['route' => ['users.faucets.destroy', $user->slug, $faucet->slug], 'method' => 'delete']) !!}
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