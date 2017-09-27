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