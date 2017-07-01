<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 25/06/2017
 * Time: 19:36
 */

namespace App\Providers\Validation;

use Illuminate\Validation\Validator;

/**
 * Class CustomValidation
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Providers\Validation
 */
class CustomValidation extends Validator
{
    /**
     * Check if the user name contains one of the barred user-names.
     *
     * Use it like so
     * 'user_name' => 'valid_user_name'
     *
     * @param  $attribute
     * @param  $value
     * @param  $parameters
     * @return mixed
     */
    public function validateValidUserName($attribute, $value, $parameters, $validator)
    {
        $barredUserNamesLowerCase = ['admin','owner', 'manager'];

        foreach ($barredUserNamesLowerCase as $a) {
            if (stripos(strtolower($value), $a) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the string value starts with a number.
     *
     * Use it like so
     * 'user_name' => 'not_start_with_number'
     *
     * @param  $attribute
     * @param  $value
     * @param  $parameters
     * @return mixed
     */
    public function validateNotStartWithNumber($attribute, $value, $parameters, $validator)
    {
        return is_numeric(substr($value, 0, 1))=== false;
    }

    /**
     * Check if the string value contains numbers.
     *
     * Use it like so
     * 'user_name' => 'no_numbers'
     *
     * @param  $attribute
     * @param  $value
     * @param  $parameters
     * @return mixed
     */
    public function validateNoNumbers($attribute, $value, $parameters, $validator)
    {
        return preg_match("/^([^0-9]+)$/", $value) == true;
    }

    /**
     * Check if the string value contains punctuation.
     *
     * Use it like so
     * 'user_name' => 'no_punctuation'
     *
     * @param  $attribute
     * @param  $value
     * @param  $parameters
     * @return mixed
     */
    public function validateNoPunctuation($attribute, $value, $parameters, $validator)
    {
        return preg_match("/[^0-9a-z\\.\\&\\@]/i", $value) != true;
    }
}
