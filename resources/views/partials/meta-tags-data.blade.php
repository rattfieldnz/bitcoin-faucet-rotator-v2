<?php
   $yandexVerification = \App\Helpers\WebsiteMeta\WebsiteMeta::seVerificationIds()['yandex_verification'];
   $bingVerification = \App\Helpers\WebsiteMeta\WebsiteMeta::seVerificationIds()['bing_verification'];
   $languageLocale = \App\Models\MainMeta::first()->language()->first()->isoCode();
   $siteName = \App\Models\MainMeta::first()->title;
   $twitterUsername = \App\Models\MainMeta::first()->twitter_username;
   $twitterUsername = !empty($twitterUsername) ? '@' . $twitterUsername : '';
   $ogImage = env('ROOT_URL') . "/assets/images/og/bitcoin.png";
?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">

    <meta itemprop="name" content="@yield('title')">
    <meta itemprop="description" content="@yield('description')">
    <meta itemprop="image" content="{{ $ogImage }}">

    <link rel="canonical" href="{{ Illuminate\Support\Facades\Request::url() }}" />

    <meta property="og:url" content="{{ Illuminate\Support\Facades\Request::url() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:site_name" content="{{ $siteName }}" />
    <meta property="fb:admins" content="871754942861947" />
    <meta property='article:published_time' content="@yield('published-time')" />
    <meta property="article:modified_time" content="@yield('updated-published-time')" />
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:image" content="{{ $ogImage }}" />
    <meta property="og:locale" content="{{ $languageLocale }}" />

    <meta name="twitter:card" content="@yield('description')" />
    <meta name="twitter:title" content="@yield('title')" />
    <meta name="twitter:description" content="@yield('description')">
    <meta name="twitter:site" content="{{ $twitterUsername }}" />

@if(!empty($yandexVerification))
    <meta name="yandex-verification" content="{{ $yandexVerification }}" />
@endif

@if(!empty($bingVerification))
    <meta name="msvalidate.01" content="{{ $bingVerification }}" />
@endif