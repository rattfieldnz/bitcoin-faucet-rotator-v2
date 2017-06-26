<?php

namespace App\Http\Requests;

use App\Helpers\Constants;
use App\Http\Requests\Request;
use App\Models\User;

class UpdateUserRequest extends Request
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
        $rules = User::$rules;

        // Allow the admin to update their profile while leaving their user-name unchanged (can't anyway).
        if(strtolower($this->user_name) == Constants::ADMIN_SLUG){
            $rules['user_name'] = 'min:5|max:15|required|not_start_with_number|no_punctuation|unique:users,user_name';
        }

        $rules['user_name'] = $rules['user_name'] . ', '. $this->id;
        $rules['email'] = $rules['email'] . ', '. $this->id;
        $rules['bitcoin_address'] = $rules['bitcoin_address'] . ', '. $this->id;

        if (!$this->has('password')) {
            $rules['password'] = [
                'confirmed',
                'min:10',
                'max:20',
                'regex:/^(?=.*[a-z|A-Z])(?=.*[0-9])(?=.*\d\s)(?=.*(_|[^\w])).+$/'
            ];
            $rules['password_confirmation'] = [
                'required_with:password',
                'min:10',
                'max:20',
                'regex:/^(?=.*[a-z|A-Z])(?=.*[0-9])(?=.*\d\s)(?=.*(_|[^\w])).+$/'
            ];
        }
        //dd($rules);
        return $rules;
    }
}
