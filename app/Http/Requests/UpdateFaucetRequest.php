<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Faucet;

class UpdateFaucetRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Faucet::$rules;
        $urlUniqueKey = array_search('unique:faucets,url', $rules['url']);

        $rules['name'] = $rules['name'] . ', '. $this->id;
        $rules['url'][$urlUniqueKey] = $rules['url'][$urlUniqueKey] . ', '. $this->id;
        return $rules;
    }
}
