<footer id="footer-custom" class="main-footer {{ empty(Auth::user()) ? 'footer-custom-guest' : '' }} row">
    <div class="col-sm-10 zero-margin">
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
    <div class="col-sm-2" style="margin-left: -1em;">
        <?php $linkRows = 5; ?>
        @foreach(\App\Helpers\Social\SocialNetworkLinks::adminLinks($linkRows) as $rows)
            <div class="row social-links-row">
                @foreach($rows as $socialNetworkName => $link)
                    <a
                        href="{{ $link }}"
                        title="{{ ucfirst($socialNetworkName) }}"
                        class="col-sm-1 link-box"
                        target="_blank"
                    >
                        <i class="fa fa-{{ $socialNetworkName }} fa-2x" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</footer>