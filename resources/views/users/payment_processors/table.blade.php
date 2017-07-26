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
            <td>
                @if(!empty(Auth::user()) && Auth::user()->isAnAdmin())
                    {{ count($paymentProcessorFaucets) }}
                @else
                    {{ count($paymentProcessorFaucets) }}
                @endif
            </td>
            @if(count($paymentProcessorFaucets) != 0)
            <td>
                {{ $paymentProcessorFaucets->sum('min_payout') }}
                Satoshis every {{ $paymentProcessorFaucets->sum('interval_minutes') }} minutes
            </td>
            <td>
                {{ $paymentProcessorFaucets->sum('max_payout') }}
                Satoshis every {{ $paymentProcessorFaucets->sum('interval_minutes') }} minutes
            </td>
            @else
                <td>N/A</td>
                <td>N/A</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</div>