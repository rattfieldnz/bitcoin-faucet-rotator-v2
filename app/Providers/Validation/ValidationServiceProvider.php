<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 25/06/2017
 * Time: 20:28
 */

namespace App\Providers\Validation;

use Illuminate\Support\ServiceProvider;
use Validator;

/**
 * Class ValidationServiceProvider
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Providers\Validation
 */
class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        // Tell Laravel to use our custom created validator class. This class will extend the
        // normal validation class, so you can add methods and override methods.

        Validator::resolver(
            function ($translator, $data, $rules, $messages) {

                // We create our own validation class here, we will create that after this
                return new CustomValidation($translator, $data, $rules, $messages);
            }
        );
    }
}
