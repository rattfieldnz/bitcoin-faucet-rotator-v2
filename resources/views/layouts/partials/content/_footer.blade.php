@if(!empty(Auth::user()))
    <footer class="main-footer row" style="max-height: 4em !important;text-align: center">
@else
    <footer class="main-footer row" style="max-height: 4em !important;text-align: center">
@endif
    <p {{ !empty(Auth::user()) ? 'style=margin-left:-14.375em!important;' : '' }}>
        <strong>Copyright © <a href="https://www.robertattfield.com" title="Rob Attfield Web Developer">Robert Attfield</a>
            2016 to {{ \Carbon\Carbon::now()->year }}.</strong> All rights reserved.
        <strong><a href="http://freebtc.website" title="Bitcoin Faucet Rotator">View the original Bitcoin Faucet Rotator</a></strong>.
    </p>
</footer>