@if(!empty(\App\Helpers\WebsiteMeta\WebsiteMeta::addThisId()))
    <p>
        <span id="addthis" class="addthis_sharing_toolbox"></span>
        @if(Route::currentRouteName() != 'home' & Route::currentRouteName() != 'login')
        <i class="fa fa-comments fa-2x" aria-hidden="true"></i> (<a href="#disqus_thread">see comments</a>)
        @endif
    </p>
@endif