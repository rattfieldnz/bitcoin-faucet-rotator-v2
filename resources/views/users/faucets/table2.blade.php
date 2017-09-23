<p><strong>*</strong> Payout amounts are in Satoshis</p>

<div id="faucetsTable-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
<div></div>



@if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
    {!! Form::open(['route' => ['users.faucets.update-multiple', $user->slug], 'method' => 'POST', 'class' => 'form-inline']) !!}
    {!! Form::hidden('_method', 'PATCH') !!}
    {!! Form::hidden('user_id', $user->id) !!}
    {!! Form::hidden('current_route_name', Route::currentRouteName())!!}
@endif
<div class="chart">
    <div id="user-faucets-buttons" class="row">
        @if(Auth::user() != null && (Auth::user()->isAnAdmin() || (Auth::user() == $user && $user->hasPermission('create-user-faucets'))))
            @if(Route::currentRouteName() != 'users.faucets.create')
                {!! Form::button(
                    '<i class="fa fa-floppy-o" style="vertical-align: middle; margin-right:0.25em;"> </i> Save Referral Codes',
                    [
                        'type' => 'submit',
                        'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                        'style' => 'margin:0.25em 0.25em 0 0; min-width:12em;'
                    ])
                !!}
                {!! Form::button(
                    '<i class="fa fa-plus" style="vertical-align: middle; margin-right:0.25em;"></i>Add New Faucet',
                    [
                        'type' => 'button',
                        'onClick' => "location.href='" . route('users.faucets.create', ['slug' => $user->slug]) . "'",
                        'class' => 'btn btn-primary btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                        'style' => 'margin:0.25em 0.25em 0.25em 0; color: white; min-width:12em;'
                    ])
                !!}
            @endif
        @endif
        @if(Route::currentRouteName() != 'users.faucets')
            {!! Form::button(
                '<i class="fa fa-link" style="vertical-align: middle; margin-right:0.25em;"></i>View on New Page',
                [
                    'type' => 'button',
                    'onClick' => "window.open('" . route('users.faucets', ['slug' => $user->slug]) . "', '_blank')",
                    'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                    'style' => 'margin:0.25em 0 0.25em 0; color: white; min-width:12em;'
                ])
            !!}
        @endif
        {!! Form::button(
            '<i class="fa fa-link" style="vertical-align: middle; margin-right:0.25em;"></i>View Rotator',
            [
                'type' => 'button',
                'onClick' => "window.open('" . route('users.rotator', ['slug' => $user->slug]) . "', '_blank')",
                'class' => 'btn btn-primary col-lg-2 col-md-2 col-sm-3 col-xs-12',
                'style' => 'margin:0.25em 0 0.25em 0; color: white; min-width:12em;'
            ])
        !!}
    </div>
    <table id="faucetsTable"
           class="row-border hover order-column {{ !Auth::check() ? 'faucetsTable_guest' : '' }}"
           cellspacing="0"
           width="100%">
        <thead class="row">
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                <th>Referral Code</th>
            @endif
        @endif
        <th>Interval Minutes</th>
        <th>Min. Payout*</th>
        <th>Max. Payout*</th>
        <th>Payment Processors</th>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                @if(Route::currentRouteName() != 'users.faucets.create')
                    <th>Deleted?</th>
                    <th>Action</th>
                @endif
            @endif
        @endif
        </thead>
        <tbody></tbody>
    </table>
</div>
@if(Auth::user() != null && (Auth::user()->isAnAdmin() || Auth::user() == $user))
    {!! Form::close() !!}
@endif

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
        var userSlug = $('#title').data('user-slug');
        var route = laroute.route('user.faucets', {userSlug: userSlug});
        var faucetsData = getFaucetsDataAjax(route);
        var faucetsTableProgressBar = generateProgressBar("#faucetsTable-progressbar",dataTablesName);
        renderFaucetsDataTable(faucetsData, '#faucetsTable', faucetsTableProgressBar);
    });
</script>
@endpush