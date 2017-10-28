<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SocialNetworks;

class UpdateSocialNetworksRequest extends FormRequest
{

    /**
     * Get the URL to redirect to on a validation error.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('settings') . "#social-links";
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAnAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = SocialNetworks::$rules;

        $fbUniqueKey = array_search('unique:social_network_links,facebook_url', $rules['facebook_url']);
        $rules['facebook_url'][$fbUniqueKey] .= ', ' .  $this->id;

        $twitterUniqueKey = array_search('unique:social_network_links,twitter_url', $rules['twitter_url']);
        $rules['twitter_url'][$twitterUniqueKey] .= ', ' .  $this->id;

        $redditUniqueKey = array_search('unique:social_network_links,reddit_url', $rules['reddit_url']);
        $rules['reddit_url'][$redditUniqueKey] .= ', ' .  $this->id;

        $gPlusUniqueKey = array_search('unique:social_network_links,google_plus_url', $rules['google_plus_url']);
        $rules['google_plus_url'][$gPlusUniqueKey] .= ', ' .  $this->id;

        $ytUniqueKey = array_search('unique:social_network_links,youtube_url', $rules['youtube_url']);
        $rules['youtube_url'][$ytUniqueKey] .= ', ' .  $this->id;

        $tumblrUniqueKey = array_search('unique:social_network_links,tumblr_url', $rules['tumblr_url']);
        $rules['tumblr_url'][$tumblrUniqueKey] .= ', ' .  $this->id;

        $vimeoUniquekey = array_search('unique:social_network_links,vimeo_url', $rules['vimeo_url']);
        $rules['vimeo_url'][$vimeoUniquekey] .= ', ' .  $this->id;

        $vkonUniqueKey = array_search('unique:social_network_links,vkontakte_url', $rules['vkontakte_url']);
        $rules['vkontakte_url'][$vkonUniqueKey] .= ', ' .  $this->id;

        $weiboUniqueKey = array_search('unique:social_network_links,sinaweibo_url', $rules['sinaweibo_url']);
        $rules['sinaweibo_url'][$weiboUniqueKey] .= ', ' .  $this->id;

        $xingUniqueKey = array_search('unique:social_network_links,xing_url', $rules['xing_url']);
        $rules['xing_url'][$xingUniqueKey] .= ', ' .  $this->id;

        $userUniqueKey = array_search('unique:social_network_links,user_id', $rules['user_id']);
        $rules['user_id'][$userUniqueKey] .= ', ' .  $this->user_id;

        return $rules;
    }
}
