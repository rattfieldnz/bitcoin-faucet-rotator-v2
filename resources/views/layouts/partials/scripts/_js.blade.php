@if(env('APP_ENV') == 'local')
    <script src="/assets/js/mainScripts.js?{{ rand()}}"></script>
@elseif(env('APP_ENV') == 'production')
    <script src="/assets/js/mainScripts.min.js?{{ rand()}}"></script>
@else
    <script src="/assets/js/mainScripts.js?{{ rand()}}"></script>
@endif
@if(\App\Helpers\WebsiteMeta\WebsiteMeta::activatedAdBlockBlocking() == true)
    <script src="/assets/js/blockadblock/custom.blockadblock.js?{{ rand()}}"></script>
@endif
@stack('scripts')