<footer id="footer-custom" class="main-footer {{ empty(Auth::user()) ? 'footer-custom-guest' : '' }} row">
    <p>
        Copyright © <a href="https://www.robertattfield.com" title="Rob Attfield Web Developer">Robert Attfield</a>
        2016 to {{ \Carbon\Carbon::now()->year }}. All rights reserved.
    </p>
    <p>
        View our
        <strong>{!! link_to_route('privacy-policy.index', 'Privacy Policy') !!}</strong>, and
        <strong>{!! link_to_route('terms-and-conditions.index', 'Terms and Conditions') !!}</strong>.
    </p>
    <p>
        View the original <strong><a href="http://freebtc.website" title="Bitcoin Faucet Rotator">Bitcoin Faucet Rotator</a></strong>.
    </p>
</footer>