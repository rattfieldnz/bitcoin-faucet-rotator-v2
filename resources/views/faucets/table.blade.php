<p><strong>*</strong> Payout amounts are in Satoshis</p>

<div id="faucetsTable-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
<div></div>

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
<div class="chart">

    <table id="faucetsTable"
           class="row-border hover order-column {{ !Auth::check() ? 'faucetsTable_guest' : '' }}"
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
<script src="/assets/js/faucet-scripts/faucetDatatables.js?{{ rand() }}"></script>
<script>
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';
        $.ajaxSetup({
            timeout: 3600000,

            // force ajax call on all browsers
            cache: false,

            // Enables cross domain requests
            crossDomain: true,

            // Helps in setting cookie
            xhrFields: {
                withCredentials: true
            },

            beforeSend: function (xhr, type) {
                // Set the CSRF Token in the header for security
                //if (type.type !== "GET") {
                var token = Cookies.get("XSRF-TOKEN");
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('X-XSRF-Token', token);
                //}
            }
        });

        //--------------------------------
        //- DATATABLES SHOWING FAUCETS -
        //--------------------------------
        var dataTablesName = 'faucets datatable';
        var route = laroute.route('faucets');
        var faucetsData = getFaucetsDataAjax(route);
        var faucetsTableProgressBar = generateProgressBar("#faucetsTable-progressbar",dataTablesName);
        renderFaucetsDataTable(faucetsData, '#faucetsTable', faucetsTableProgressBar);
    });
</script>
@endpush