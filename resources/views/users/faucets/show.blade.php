@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
           Faucet - {!! $faucet->name !!}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @if(!empty($message))
            <div class="alert alert-info">
                {!! $message !!}
            </div>
        @endif
        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <a href="{!! route('users.faucets', $user->slug) !!}" class="btn btn-default">Back</a>


                    <div id="faucet-info" class="table table-responsive">
                        <table class="table table-striped table bordered">
                            <thead>
                            <th>Faucet URL</th>
                            <th>Interval (minutes)</th>
                            <th>Minimum Payout (satoshis)</th>
                            <th>Maximum Payout (satoshis)</th>
                            <th>Payment Processor/s</th>
                            <th>Referral Program?</th>
                            <th>Ref. Payout %</th>
                            <th>Comments</th>
                            <th>Status</th>
                            <th>Low Balance <br><small>(less than 10,000 SAT)</small></th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    {!! link_to($faucet->url . App\Helpers\Functions\Faucets::getUserFaucetRefCode($user, $faucet), $faucet->name, ['target' => 'blank', 'title' => $faucet->name]) !!}
                                </td>
                                <td>{{ $faucet->interval_minutes }}</td>
                                <td>{{ $faucet->min_payout }}</td>
                                <td>{{ $faucet->max_payout }}</td>
                                <td>
                                    @if($faucet->paymentProcessors)
                                        @if(count($faucet->paymentProcessors) == 0)
                                            None. Please add one (or more) for this faucet
                                        @else
                                            <ul class="faucet-payment-processors">
                                                @foreach($faucet->paymentProcessors as $paymentProcessor)
                                                    <li>{!! link_to(route('payment-processors.show', $paymentProcessor->slug), $paymentProcessor->name, ['target' => 'blank', 'title' => $paymentProcessor->name]) !!}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endif

                                </td>
                                <td>{{ $faucet->hasRefProgram() }}</td>
                                <td>{{ $faucet->ref_payout_percent }}</td>
                                <td>{{ $faucet->comments }}</td>
                                <td>{{ $faucet->status() }}</td>
                                <td>{{ $faucet->lowBalanceStatus() }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    @if($faucet->is_paused == false)
                        <iframe sandbox="allow-forms allow-scripts allow-pointer-lock allow-same-origin" src="{{ $faucet->url }}" id="faucet-iframe"></iframe>
                    @else
                        <p>This faucet has been paused from showing in rotation.</p>

                        @if(Auth::user() && $user == Auth::user() || Auth::user()->hasRole('owner'))
                            <p>You can {!! link_to('/users/' . $user->slug . '/faucets/' . $faucet->slug . '/edit', 'edit this faucet') !!} to re-enable it in rotation.</p>
                        @else
                            <p>Please contact the administrator if you would like this faucet re-enabled.</p>
                        @endif
                    @endif
                    <a href="{!! route('users.faucets', $user->slug) !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
