<?php

use App\Models\MainMeta;
use App\Models\SocialNetworks;
use Artesaos\SEOTools\Facades\JsonLd;

$socialNetworks = SocialNetworks::all();
$sameAs = [];

$fb = $socialNetworks->pluck('facebook_url')->first();
!empty($fb) ? array_push($sameAs, $fb) : null;

$twitter = $socialNetworks->pluck('twitter_url')->first();
!empty($twitter) ? array_push($sameAs, $twitter) : null;

$reddit = $socialNetworks->pluck('reddit_url')->first();
!empty($reddit) ? array_push($sameAs, $reddit) : null;

$gplus = $socialNetworks->pluck('google_plus_url')->first();
!empty($gplus) ? array_push($sameAs, $gplus) : null;

$yt = $socialNetworks->pluck('youtube_url')->first();
!empty($yt) ? array_push($sameAs, $yt) : null;

$tumblr = $socialNetworks->pluck('tumblr_url')->first();
!empty($tumblr) ? array_push($sameAs, $tumblr) : null;

$vimeo = $socialNetworks->pluck('vimeo_url')->first();
!empty($tumblr) ? array_push($sameAs, $vimeo) : null;

$vk = $socialNetworks->pluck('vkontakte_url')->first();
!empty($vk) ? array_push($sameAs, $vimeo) : null;

$sw = $socialNetworks->pluck('sinaweibo_url')->first();
!empty($sw) ? array_push($sameAs, $sw) : null;

$xing = $socialNetworks->pluck('xing_url')->first();
!empty($xing) ? array_push($sameAs, $xing) : null;

JsonLd::setType("Website")
    ->addValue('name', MainMeta::first()->page_main_title)
    ->setTitle(MainMeta::first()->page_main_title)
    ->setDescription(MainMeta::first()->description)
    ->setSite(MainMeta::first()->page_main_title)
    ->setImage(asset('assets/images/og/bitcoin.png'))
    ->addValue('sameAs', $sameAs);

JsonLd::setUrl(\Illuminate\Support\Facades\Config::get('app.url'));

?>
