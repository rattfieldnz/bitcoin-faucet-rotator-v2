<p><strong>*</strong> Payout amounts are in Satoshis</p>

<div id="paymentProcessorsTable-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
<div></div>

@if(Route::currentRouteName() != 'users.payment-processors')
    @if(!empty(Auth::user() && Auth::user()->isAnAdmin()))

        {!! Form::button(
            '<i class="fa fa-2x fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New Faucet',
            [
                'type' => 'button',
                'onClick' => "location.href='" . route('payment-processors.create') . "'",
                'class' => 'btn btn-success col-lg-2 col-md-2 col-sm-2 col-xs-12',
                'style' => 'margin:0.25em 0 0.25em 0; color: white; min-width:12em;'
            ])
        !!}
    @endif
@endif

<div class="chart">

    <table id="paymentProcessorsTable"
           class="row-border hover order-column {{ !Auth::check() ? 'paymentProcessorsTable_guest' : 'paymentProcessorsTable_auth' }}"
           cellspacing="0"
           width="100%">
        <thead>
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                    <th>Id</th>
                @endif
            @endif
            <th>Name</th>
            <th>Faucets</th>
            <th>Rotators</th>
            <th>No. of Faucets</th>
            <th>Min. Claimable</th>
            <th>Max. Claimable</th>
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
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
            <th>Faucets</th>
            <th>Rotators</th>
            <th>No. of Faucets</th>
            <th>Min. Claimable</th>
            <th>Max. Claimable</th>
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                    <th>Action</th>
                @endif
            @endif
        </tr>
        </tfoot>
    </table>
</div>

@push('scripts')
<script src="{{ asset("/assets/js/datatables.net/datatables.min.js?" . rand()) }}"></script>
<script src="{{ asset("/assets/js/payment-processor-scripts/paymentProcessorDatatables.min.js?" . rand()) }}"></script>
<script>
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';

        //--------------------------------
        //- DATATABLES SHOWING FAUCETS -
        //--------------------------------
        var dataTablesName = 'payment processors datatable';
        var userSlug = $('#title').data('user-slug');
        var route = laroute.url('api/v1/users', [userSlug, 'payment-processors']);
        var faucetsData = getFaucetsDataAjax(route);
        var paymentProcessorsTableProgressBar = generateProgressBar("#paymentProcessorsTable-progressbar",dataTablesName);
        renderPaymentProcessorsDataTable(faucetsData, '#paymentProcessorsTable', paymentProcessorsTableProgressBar);
    });
</script>
@endpush