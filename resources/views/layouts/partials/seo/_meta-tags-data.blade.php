<?php
   $yandexVerification = \App\Helpers\WebsiteMeta\WebsiteMeta::seVerificationIds()['yandex_verification'];
   $bingVerification = \App\Helpers\WebsiteMeta\WebsiteMeta::seVerificationIds()['bing_verification'];
?>

{!! \Artesaos\SEOTools\Facades\SEOTools::generate() !!}

@if(!empty($yandexVerification))
    <meta name="yandex-verification" content="{{ $yandexVerification }}" />
@endif

@if(!empty($bingVerification))
    <meta name="msvalidate.01" content="{{ $bingVerification }}" />
@endif
