<div id="usersTable-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
<div></div>

<div class="chart">
    <table id="usersTable" class="row-border hover order-column table-hover table-responsive {{ !Auth::check() ? 'usersTable_guest' : 'usersTable_auth' }}"
           cellspacing="0" width="100%">
        <thead>
        @if(Auth::check() && Auth::user()->isAnAdmin())
            <th>Id</th>
        @endif
        <th>User Name</th>
        @if(Auth::check() && Auth::user()->isAnAdmin())
            <th>Role</th>
            <th>Is Admin</th>
            <th>Deleted?</th>
        @endif
        <th>Faucets</th>
        <th>No. of Faucets</th>
        <th>Payment Processors</th>
        @if(Auth::check() && Auth::user()->isAnAdmin())
            <th>Action</th>
        @endif
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        @if(Auth::check() && Auth::user()->isAnAdmin())
            <th>Id</th>
        @endif
        <th>User Name</th>
        @if(Auth::check() && Auth::user()->isAnAdmin())
            <th>Role</th>
            <th>Is Admin</th>
            <th>Deleted?</th>
        @endif
        <th>Faucets</th>
        <th>No. of Faucets</th>
        <th>Payment Processors</th>
        @if(Auth::check() && Auth::user()->isAnAdmin())
            <th>Action</th>
        @endif
        </tfoot>
    </table>
</div>

@push('scripts')
<script src="/assets/js/datatables.net/datatables.min.js?{{ rand() }}"></script>
<script src="/assets/js/user-scripts/usersDatatables.min.js?{{ rand() }}"></script>
<script>
    $(function () {

        $.fn.dataTable.ext.errMode = 'none';

        //--------------------------------
        //- DATATABLES SHOWING USERS -
        //--------------------------------
        var dataTablesName = 'users datatable';
        var route = laroute.route('users');
        var usersData = getUsersDataAjax(route);
        var usersTableProgressBar = generateProgressBar("#usersTable-progressbar",dataTablesName);
        renderUsersDataTable(usersData, '#usersTable', usersTableProgressBar);

        var table = $.fn.dataTable.fnTables(true);
        if ( table.length > 0 ) {
            $(table).dataTable().fnAdjustColumnSizing();
        }
    });
</script>
@endpush