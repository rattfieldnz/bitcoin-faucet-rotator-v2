<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="payment-processors-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        <th>Url</th>
        <th>Faucets</th>
    </thead>
    <tbody>
    @foreach($paymentProcessors as $paymentProcessor)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                    <td>{!! $paymentProcessor->id !!}</td>
                @endif
            @endif
            <td>{!! $paymentProcessor->name !!}</td>
            <td>{!! $paymentProcessor->url !!}</td>
            <td>
                <a href="{!! route('users.payment-processors.faucets', ['userSlug' => $user->userSlug(), 'paymentProcessorSlug' => $paymentProcessor->slug]) !!}">View Faucets</a>
                </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>