<div id="show-no-iframe-content">
    <h3 style="font-size: 3em;">Sorry!</h3>

    <p>This faucet either: cannot be shown in iframes, is redirected from it's original domain,
        or there is an issue with the faucet's domain name.
        Please <a id="faucet-link" href="" target="_blank" title="">visit the faucet in a new tab/window</a>.
    </p>
    @if(Auth::user() && Auth::user()->isAnAdmin())
        <p>You can <a id="edit-faucet-link" href="" target="_blank" title="check issues with this faucet">check this faucet</a> to make any changes
            (for example, if the faucet's URL / domain name is having issues).
        </p>
    @else
        <p>Please contact the administrator if you are experiencing issues with the faucet's direct URL / domain name.</p>
    @endif
</div>