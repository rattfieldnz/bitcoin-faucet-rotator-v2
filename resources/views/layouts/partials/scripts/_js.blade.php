@if(env('APP_ENV') == 'local')
    <script src="{{ asset("/assets/js/mainScripts.js?" . rand()) }}"></script>
@elseif(env('APP_ENV') == 'production')
    <script src="{{ asset("/assets/js/mainScripts.min.js?" . rand()) }}"></script>
@else
    <script src="{{ asset("/assets/js/mainScripts.js?" . rand()) }}"></script>
@endif
@if(\App\Helpers\WebsiteMeta\WebsiteMeta::activatedAdBlockBlocking() == true)
    <script src="{{ asset("/assets/js/blockadblock/custom.blockadblock.js" . rand()) }}"></script>
@endif
@stack('scripts')