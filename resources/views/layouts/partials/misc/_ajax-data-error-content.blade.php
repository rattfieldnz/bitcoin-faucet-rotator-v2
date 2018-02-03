<div id="show-ajax-data-error-content">
    <h3 style="font-size: 3em;">Sorry!</h3>

    <p>There has been an error in retrieving the necessary data to show the faucet.</p>
    @if(Auth::check() && Auth::user()->isAnAdmin())
        <ul>
            <li>Error code: <span id="error-code"></span></li>
            <li>Error message: <span id="error-message"></span></li>	
            @if(!empty(Sentry::getLastEventID()))
            <li>Sentry ID: {{ Sentry::getLastEventID() }}.</li>
            @endif
        </ul>
        <p>You can forward these details to your developer for further troubleshooting.</p>
    @else
        @if(!empty(Sentry::getLastEventID()))
        <p>Please send this ID with your support request: {{ Sentry::getLastEventID() }}.</p>
        @endif
    @endif
</div>