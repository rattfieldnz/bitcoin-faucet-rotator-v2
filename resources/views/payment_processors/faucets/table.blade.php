<p><strong>*</strong> Payout amounts are in Satoshis</p>

<div id="faucetsTable-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
<div></div>

<div class="chart">

    <table id="faucetsTable"
           class="row-border hover order-column {{ !Auth::check() ? 'faucetsTable_guest' : 'faucetsTable_auth' }}"
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

@push('scripts')
<script src="/assets/js/datatables.net/datatables.min.js?{{ rand() }}"></script>
<script src="/assets/js/faucet-scripts/faucetDatatables.min.js?{{ rand() }}"></script>
<script>
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';

        //--------------------------------
        //- DATATABLES SHOWING FAUCETS -
        //--------------------------------
        var dataTablesName = 'faucets datatable';
        var paymentProcessorSlug = $('#title').data('payment-processor-slug');
        var route = laroute.route('payment-processor.faucets', {paymentProcessorSlug: paymentProcessorSlug});
        var faucetsData = getFaucetsDataAjax(route);
        var faucetsTableProgressBar = generateProgressBar("#faucetsTable-progressbar",dataTablesName);
        renderFaucetsDataTable(faucetsData, '#faucetsTable', faucetsTableProgressBar);
    });
</script>
@endpush