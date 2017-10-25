<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SocialNetworks;

class UpdateSocialNetworksRequest extends FormRequest
{

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

        $rules['facebook_url'] .= ', ' .  $this->user_id;
        $rules['twitter_url'] .= ', ' .  $this->user_id;
        $rules['reddit_url'] .= ', ' .  $this->user_id;
        $rules['google_plus_url'] .= ', ' .  $this->user_id;
        $rules['youtube_url'] .= ', ' .  $this->user_id;
        $rules['tumblr_url'] .= ', ' .  $this->user_id;
        $rules['vimeo_url'] .= ', ' .  $this->user_id;
        $rules['vkontakte_url'] .= ', ' .  $this->user_id;
        $rules['sinaweibo_url'] .= ', ' .  $this->user_id;
        $rules['xing_url'] .= ', ' .  $this->user_id;

        return $rules;
    }
}
