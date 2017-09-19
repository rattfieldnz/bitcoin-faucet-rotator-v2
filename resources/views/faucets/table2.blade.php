<p><strong>*</strong> Payout amounts are in Satoshis</p>

<div id="faucetsTable-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
<div></div>
<div class="chart" style="width: 100%">

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
    <table id="faucetsTable" class="row-border hover order-column" cellspacing="0">
        <thead>
        <tr>
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
                    <th>Action</th>
                @endif
            @endif
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
        <tr>
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
                    <th>Action</th>
                @endif
            @endif
        </tr>
        </tfoot>
    </table>
</div>