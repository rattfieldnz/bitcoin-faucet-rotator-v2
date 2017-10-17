<p><strong>*</strong> Payout amounts are in Satoshis</p>

@if(Auth::check() && (Auth::user()->id == $user->id || Auth::user()->isAnAdmin()))
    <div class="alert alert-info">
        <p>
            <i class="fa fa-info-circle" aria-hidden="true" style="font-size: 2em; margin-right: 0.5em;"></i>
            Faucets without referral codes will not be shown to visitors - in the faucet list and rotator.
        </p>
    </div>
@endif

<div id="faucetsTable-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
<div></div>

@if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
    {!! Form::open(['route' => ['users.faucets.update-multiple', $user->slug], 'method' => 'POST', 'class' => 'form-inline']) !!}
    {!! Form::hidden('_method', 'PATCH') !!}
    {!! Form::hidden('user_id', $user->id) !!}
    {!! Form::hidden('payment_processor', $paymentProcessor->slug) !!}
    {!! Form::hidden('current_route_name', Route::currentRouteName())!!}
@endif

<div class="chart">

    <div id="user-faucets-buttons" class="row">
        @if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
            {!! Form::button(
                '<i class="fa fa-floppy-o" style="vertical-align: middle; margin-right:0.25em;"></i>Save Referral Codes',
                [
                    'type' => 'submit',
                    'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                    'style' => 'margin:0.25em 0.25em 0 0; color: white; min-width:12em;'
                ])
            !!}
        @endif
        {!! Form::button(
            '<i class="fa fa-link" style="vertical-align: middle; margin-right:0.25em;"></i>View ' . $paymentProcessor->name . ' Rotator',
            [
                'type' => 'button',
                'onClick' => "window.open('" . route('users.payment-processors.rotator', ['userSlug' => $user->slug, 'paymentProcessorSlug' => $paymentProcessor->slug]) . "', '_blank')",
                'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                'style' => 'margin:0.25em 0 0.25em 0; color: white; min-width:12em;'
            ])
        !!}
    </div>

    <table id="faucetsTable"
           class="row-border hover order-column table-hover table-responsive {{ !Auth::check() ? 'faucetsTable_guest' : 'faucetsTable_auth' }}"
           cellspacing="0" width="100%">
        <thead>
        @include('users.faucets.partials._thead_and_tfoot')
        </thead>
        <tbody></tbody>
        <tfoot>
        @include('users.faucets.partials._thead_and_tfoot')
        </tfoot>
    </table>
</div>
@if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
    {!! Form::close() !!}
@endif

@push('scripts')
<script src="{{ asset("/assets/js/datatables.net/datatables.min.js?" . rand()) }}"></script>
<script src="{{ asset("/assets/js/faucet-scripts/faucetDatatables.min.js?" . rand()) }}"></script>
<script>
    $(function () {

        $.fn.dataTable.ext.errMode = 'none';

        //--------------------------------
        //- DATATABLES SHOWING FAUCETS -
        //--------------------------------
        var dataTablesName = 'faucets datatable';
        var userSlug = $('#title').data('user-slug');
        var paymentProcessorSlug = $('#title').data('payment-processor-slug');
        var route = laroute.route('user.payment-processor-faucets', {userSlug: userSlug, paymentProcessorSlug: paymentProcessorSlug});

        var faucetsData = getFaucetsDataAjax(route);
        var faucetsTableProgressBar = generateProgressBar("#faucetsTable-progressbar",dataTablesName);
        renderFaucetsDataTable(faucetsData, '#faucetsTable', faucetsTableProgressBar);
    });
</script>
@endpush