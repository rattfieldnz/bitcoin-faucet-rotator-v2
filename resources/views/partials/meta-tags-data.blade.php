<?php
   $yandexVerification = \App\Helpers\WebsiteMeta\WebsiteMeta::seVerificationIds()['yandex_verification'];
   $bingVerification = \App\Helpers\WebsiteMeta\WebsiteMeta::seVerificationIds()['bing_verification'];
?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">

    <meta property="og:url" content="{{ Illuminate\Support\Facades\Request::url() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:image" content="{{ env('ROOT_URL') }}/assets/images/og/bitcoin.png" />

@if(!empty($yandexVerification))
<meta name="yandex-verification" content="{{ \App\Helpers\WebsiteMeta\WebsiteMeta::seVerificationIds()['yandex_verification'] }}" />
@endif

@if(!empty($bingVerification))
<meta name="msvalidate.01" content="{{ \App\Helpers\WebsiteMeta\WebsiteMeta::seVerificationIds()['bing_verification'] }}" />
@endif