<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="payment-processors-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        <th>Faucets</th>
        <th>No. Of Faucets</th>
        <th>Min. Claimable</th>
        <th>Max. Claimable</th>

        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
            <th colspan="3">Action</th>
            @endif
        @endif
    </thead>
    <tbody>
    @foreach($paymentProcessors as $paymentProcessor)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
                    <td>{!! $paymentProcessor->id !!}</td>
                @endif
            @endif
            <td>
                {!! link_to_route('payment-processors.show', $paymentProcessor->name, ['slug' => $paymentProcessor->slug]) !!}
            </td>
            <td>{!! link_to_route('payment-processors.faucets', $paymentProcessor->name . " Faucets", ['slug' => $paymentProcessor->slug]) !!}</td>
            <td>{{ count($paymentProcessor->faucets()->get()) }}</td>
            <td>{{ $paymentProcessor->faucets()->sum('min_payout') }} Satoshis every {{ $paymentProcessor->faucets()->sum('interval_minutes') }} minutes</td>
            <td>{{ $paymentProcessor->faucets()->sum('max_payout') }} Satoshis every {{ $paymentProcessor->faucets()->sum('interval_minutes') }} minutes</td>

            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
            <td>
                <div class='btn-group'>
                    <a href="{!! route('payment-processors.edit', ['slug' => $paymentProcessor->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    @if($paymentProcessor->isDeleted())
                        {!! Form::open(['route' => ['payment-processors.delete-permanently', $paymentProcessor->slug], 'method' => 'delete']) !!}
                        {!! csrf_field() !!}
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The payment processor will be PERMANENTLY deleted!')"]) !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['payment-processors.restore', $paymentProcessor->slug], 'method' => 'patch']) !!}
                        {!! csrf_field() !!}
                        {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted payment processor?')"]) !!}
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['route' => ['payment-processors.destroy', 'slug' => $paymentProcessor->slug], 'method' => 'delete']) !!}
                        {!! csrf_field() !!}
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
            </td>
                @endif
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</div>