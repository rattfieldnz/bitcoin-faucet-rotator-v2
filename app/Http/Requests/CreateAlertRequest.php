<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class CreateAlertRequest
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Requests
 */
class CreateAlertRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAnAdmin();
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->flash();
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo(route('alerts.create'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Alert::$rules;
    }
}
