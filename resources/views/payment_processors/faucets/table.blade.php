<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="faucets-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        <th>Url</th>
        <th>Interval Minutes</th>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
                <th>Has Been Deleted</th>
            @endif
        @endif
        <th>Action</th>
    </thead>
    <tbody>
    @foreach($faucets as $faucet)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
                    <td>{!! $faucet->id !!}</td>
                @endif
            @endif
            <td>{!! $faucet->name !!}</td>
            <td>{!! $faucet->url . ($faucet->users()->where('is_admin', true)->first() != null ? $faucet->users()->where('is_admin', true)->first()->pivot->referral_code : "") !!}</td>
            <td>{!! $faucet->interval_minutes !!}</td>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
                    <td>{!! $faucet->isDeleted() == true ? "Yes" : "No" !!}</td>
                @endif
            @endif
            <td>
                <div class='btn-group'>
                    <a href="{!! route('faucets.show', ['slug' => $faucet->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @if(Auth::user() != null)
                        @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
                            <a href="{!! route('faucets.edit', ['slug' => $faucet->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            @if($faucet->isDeleted())
                                @if(Auth::user()->hasRole('owner'))
                                    {!! Form::open(['route' => ['faucets.delete-permanently', $faucet->slug], 'method' => 'delete']) !!}
                                    @if(!empty($paymentProcessor))
                                        {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                                    @endif
                                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"]) !!}
                                    {!! Form::close() !!}
                                @endif
                                @if(Auth::user()->hasRole('owner'))
                                    @if(Auth::user()->hasPermission('restore-faucets'))
                                        {!! Form::open(['route' => ['faucets.restore', $faucet->slug], 'method' => 'patch']) !!}
                                        @if(!empty($paymentProcessor))
                                            {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                                        @endif
                                        {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted faucet?')"]) !!}
                                        {!! Form::close() !!}
                                    @endif
                                @endif
                            @else
                                @if(Auth::user()->hasRole('owner'))
                                    @if(Auth::user()->hasPermission('soft-delete-faucets'))
                                        {!! Form::open(['route' => ['faucets.destroy', 'slug' => $faucet->slug], 'method' => 'delete']) !!}
                                        @if(!empty($paymentProcessor))
                                            {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
                                        @endif
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                        {!! Form::close() !!}
                                    @endif
                                @endif
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