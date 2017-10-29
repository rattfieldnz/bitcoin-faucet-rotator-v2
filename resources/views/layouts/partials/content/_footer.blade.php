<footer id="footer-custom" class="main-footer {{ empty(Auth::user()) ? 'footer-custom-guest' : '' }} row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 zero-margin">
        <p class="word-wrap-white-space">
            Copyright © <a href="https://www.robertattfield.com" title="Rob Attfield Web Developer">Robert Attfield</a>
            2016 to {{ \Carbon\Carbon::now()->year }}. All rights reserved.
        </p>
        <p>
            @include('layouts.partials.navigation._footer_alternate_menu')
        </p>
        <p class="word-wrap-white-space">
            View the original <strong><a href="http://freebtc.website" title="Bitcoin Faucet Rotator">Bitcoin Faucet Rotator</a></strong>.
        </p>
    </div>
</footer>