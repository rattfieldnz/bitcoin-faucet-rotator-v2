<div id="show-ajax-data-error-content">
    <h3 style="font-size: 3em;">Sorry!</h3>

    <p>There has been an error in retrieving the necessary data to show the faucet.</p>
    @if(Auth::check() && Auth::user()->isAnAdmin())
        <ul>
            <li>Error code: <span id="error-code"></span></li>
            <li>Error message: <span id="error-message"></span></li>
            <li>Sentry ID: <span id="sentry-id"></span></li>
        </ul>
        <p>You can forward these details to your developer for further troubleshooting.</p>
    @else
        <p>Please send the following support code to admin: <strong><span id="sentry-id"></span></strong>.
            This code will be used for troubleshooting what may have caused the error.</p>
    @endif
</div>