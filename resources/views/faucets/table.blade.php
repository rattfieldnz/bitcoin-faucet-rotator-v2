<p><strong>*</strong> Payout amounts are in Satoshis</p>
<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="faucets-table">
    <thead class="row">
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        <th>Interval Minutes</th>
        <th>Min. Payout*</th>
        <th>Max. Payout*</th>
        <th>Payment Processors</th>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <th>Deleted?</th>
            @endif
        @endif
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <th>Action</th>
            @endif
        @endif
    </thead>
    <tbody class="row">
    @foreach($faucets as $faucet)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                    <td>{!! $faucet->id !!}</td>
                @endif
            @endif
            <td>
                {!! link_to_route('faucets.show',$faucet->name,['slug' => $faucet->slug],['style' => 'text-decoration:underline;']) !!}
            </td>
            <td>{!! $faucet->interval_minutes !!}</td>
            <td>{!! $faucet->min_payout !!}</td>
            <td>{!! $faucet->max_payout !!}</td>
            <td>
                @if($faucet->paymentProcessors)
                    @if(count($faucet->paymentProcessors) == 0)
                        None. Please add one (or more) for this faucet
                    @else
                        <ul>
                            @foreach($faucet->paymentProcessors as $p)
                                <li>
                                    {!! link_to_route('payment-processors.show', $p->name, ['slug' => $p->slug],['style' => 'text-decoration:underline;']) !!}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </td>
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                <td>{!! $faucet->isDeleted() == true ? "Yes" : "No" !!}</td>
                <td>
                    <div class='btn-group'>
                        <a href="{!! route('faucets.edit', ['slug' => $faucet->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        @if($faucet->isDeleted())
                            {!! Form::open(['route' => ['faucets.delete-permanently', $faucet->slug], 'method' => 'delete']) !!}
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"]) !!}
                            {!! Form::close() !!}
                            {!! Form::open(['route' => ['faucets.restore', $faucet->slug], 'method' => 'patch']) !!}
                            {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted faucet?')"]) !!}
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(['route' => ['faucets.destroy', 'slug' => $faucet->slug], 'method' => 'delete']) !!}
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </td>
                @endif
            @endif
        </tr>
    @endforeach

    @if(Route::currentRouteName() != 'faucets.index')
        @if(!empty(Auth::user() && Auth::user()->isAnAdmin()))

            {!! Form::button(
                '<i class="fa fa-2x fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New Faucet',
                [
                    'type' => 'button',
                    'onClick' => "location.href='" . route('faucets.create') . "'",
                    'class' => 'btn btn-success col-lg-2 col-md-2 col-sm-2 col-xs-12',
                    'style' => 'margin:0.25em 0 0.25em 0; color: white; min-width:12em;'
                ])
            !!}
        @endif
    @endif
    </tbody>
</table>
</div>