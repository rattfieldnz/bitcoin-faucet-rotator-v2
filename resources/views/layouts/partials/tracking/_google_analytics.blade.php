<?php
use App\Models\MainMeta;

$mainMeta = MainMeta::firstOrFail();
?>

@if(!empty($mainMeta->google_analytics_id))
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $mainMeta->google_analytics_id }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ $mainMeta->google_analytics_id }}');
</script>

@endif
