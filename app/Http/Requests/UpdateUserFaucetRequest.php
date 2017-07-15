<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class UpdateUserFaucetRequest
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Requests
 */
class UpdateUserFaucetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !empty(Auth::user()) && ($this->user()->isAnAdmin() || $this->user()->hasRole('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|numeric|exists:users,id',
            'faucet_id' => 'required|numeric|exists:faucets,id',
            'referral_code' => 'sometimes|string|max:255',
        ];
    }
}
