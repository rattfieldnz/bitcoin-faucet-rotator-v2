@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Faucet - {!! $faucet->name !!}</h1>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <p class="pull-right">
                    <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px;margin-left:10px;" href="{!! route('faucets.edit', ['slug' => $faucet->slug]) !!}">Edit Current Faucet</a>
                    <a class="btn btn-primary btn-success pull-right" style="margin-top: -10px;margin-bottom: 5px;margin-left:10px;" href="{!! route('faucets.create') !!}">Add New Faucet</a>
                    @if($faucet->isDeleted())
                        {!! Form::open(['route' => ['faucets.delete-permanently', $faucet->slug], 'method' => 'delete', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Permanently Delete Current Faucet', ['type' => 'submit', 'class' => 'btn btn-danger', 'style' => 'margin-top:-10px;margin-bottom: 5px;', 'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"]) !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['faucets.restore', $faucet->slug], 'method' => 'patch', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Restore Current Faucet', ['type' => 'submit', 'class' => 'btn btn-info', 'style' => 'margin-top: -10px;margin-bottom:5px;margin-right:10px;', 'onclick' => "return confirm('Are you sure you want to restore this archived faucet?')"]) !!}
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['route' => ['faucets.destroy', 'slug' => $faucet->slug], 'method' => 'delete', 'class' => 'form-inline pull-right']) !!}
                        {!! Form::button('Archive/Delete Current Faucet', ['type' => 'submit', 'class' => 'btn btn-warning', 'style' => 'margin-top: -10px;margin-bottom: 5px;', 'onclick' => "return confirm('Are you sure you want to archive this faucet?')"]) !!}
                        {!! Form::close() !!}
                    @endif
                </p>
            @endif
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @include('faucets.partials._message-info')
        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')

        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <a href="{!! route('faucets.index') !!}" class="btn btn-default">Back</a>


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
                                    {!! link_to($faucetUrl, $faucet->name, ['target' => 'blank', 'title' => $faucet->name]) !!}
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
                        <iframe sandbox="allow-forms allow-scripts allow-pointer-lock allow-same-origin" src="{{ $faucetUrl }}" id="faucet-iframe"></iframe>
                    @else
                        <p>This faucet has been paused from showing in rotation.</p>

                        @if(Auth::user() && Auth::user()->isAnAdmin())
                            <p>You can {!! link_to('/faucets/' . $faucet->slug . '/edit', 'edit this faucet') !!} to re-enable it in rotation.</p>
                        @else
                            <p>Please contact the administrator if you would like this faucet re-enabled.</p>
                        @endif
                    @endif
                    <a href="{!! route('faucets.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('google-analytics')
    @include('layouts.partials.tracking._google_analytics')
@endpush