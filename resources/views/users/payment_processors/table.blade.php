<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="payment-processors-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        <th>Faucets</th>
        <th>No. Of Faucets</th>
        <th>Min. Claimable</th>
        <th>Max. Claimable</th>
    </thead>
    <tbody>
    @foreach($paymentProcessors as $paymentProcessor)
        <?php
             $paymentProcessorFaucets = \App\Helpers\Functions\PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);
        ?>
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                    <td>{!! $paymentProcessor->id !!}</td>
                @endif
            @endif
            <td>
                {{ $paymentProcessor->name }}
            </td>
            <td>{!! link_to_route('users.payment-processors.faucets', $paymentProcessor->name . " Faucets", ['userSlug' => $user->slug, 'paymentProcessorSlug' => $paymentProcessor->slug]) !!}</td>
            <td>{{ count($paymentProcessorFaucets) }}</td>
            <td>
                {{ $paymentProcessorFaucets->sum('min_payout') }}
                Satoshis every {{ $paymentProcessorFaucets->sum('interval_minutes') }} minutes
            </td>
            <td>
                {{ $paymentProcessorFaucets->sum('max_payout') }}
                Satoshis every {{ $paymentProcessorFaucets->sum('interval_minutes') }} minutes
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>