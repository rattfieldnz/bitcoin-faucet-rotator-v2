@if($user->isAnAdmin())
    @include('faucets.table')
@else
    @include('users.faucets.table')
@endif