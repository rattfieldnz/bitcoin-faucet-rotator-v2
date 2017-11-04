@if(\App\Helpers\WebsiteMeta\WebsiteMeta::disqusShortName() && !empty($disqusIdentifier) && !empty($currentUrl))
    <?php
        $disqusShortName = \App\Helpers\WebsiteMeta\WebsiteMeta::disqusShortName();
    ?>
    <div id="disqus_thread"></div>
    <script>
        var disqus_shortname = '{{ $disqusShortName }}';
        var disqus_config = function () {
            this.page.url = '{{ $currentUrl }}';
            this.page.identifier = '{{ $disqusIdentifier }}';
        };

        (function() { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            s.src = 'https://' + disqus_shortname + '.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

    @push('scripts')
    <script id="dsq-count-scr" src="https://{{ $disqusShortName }}.disqus.com/count.js" async></script>
    @endpush
@endif
