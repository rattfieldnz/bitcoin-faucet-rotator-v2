<?php

namespace App\Helpers\Social;

use App\Helpers\Functions\Users;

/**
 * Class SocialNetworkLinks
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 *
 * @package App\Helpers\Social
 */
class SocialNetworkLinks
{
    /**
     * @return array
     */
    public static function adminLinks()
    {

        $adminUser = Users::adminUser();
        $socialLinks = $adminUser->socialNetworkLinks()->first();

        $existingLinks = [];

        if (!empty($socialLinks->facebook_url)) {
            $existingLinks['facebook'] = $socialLinks->facebook_url;
        }
        if (!empty($socialLinks->twitter_url)) {
            $existingLinks['twitter'] = $socialLinks->twitter_url;
        }
        if (!empty($socialLinks->reddit_url)) {
            $existingLinks['reddit'] = $socialLinks->reddit_url;
        }
        if (!empty($socialLinks->google_plus_url)) {
            $existingLinks['google-plus'] = $socialLinks->google_plus_url;
        }
        if (!empty($socialLinks->youtube_url)) {
            $existingLinks['youtube'] = $socialLinks->youtube_url;
        }
        if (!empty($socialLinks->tumblr_url)) {
            $existingLinks['tumblr'] = $socialLinks->tumblr_url;
        }
        if (!empty($socialLinks->vimeo_url)) {
            $existingLinks['vimeo'] = $socialLinks->vimeo_url;
        }
        if (!empty($socialLinks->vkontakte_url)) {
            $existingLinks['vk'] = $socialLinks->vkontakte_url;
        }
        if (!empty($socialLinks->sinaweibo_url)) {
            $existingLinks['weibo'] = $socialLinks->sinaweibo_url;
        }
        if (!empty($socialLinks->xing_url)) {
            $existingLinks['xing'] = $socialLinks->xing_url;
        }

        return $existingLinks;
    }
}
