<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Faucet;

/**
 * Class UpdateFaucetRequest
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Requests
 */
class UpdateFaucetRequest extends Request
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
        $rules = Faucet::$rules;
        $urlUniqueKey = array_search('unique:faucets,url', $rules['url']);

        $rules['name'] = $rules['name'] . ', '. $this->id;
        $rules['url'][$urlUniqueKey] = $rules['url'][$urlUniqueKey] . ', '. $this->id;
        return $rules;
    }
}
