<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 28/08/2017
 * Time: 19:57
 */

namespace App\Exceptions\Formatters;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Debug\Exception\FlattenException;

class GeneralExceptionFormatter
{
    public static function make(Exception $e)
    {
        $statusCode = FlattenException::create($e)->getStatusCode();

        return response()->view('errors.' . $statusCode, [], $statusCode);
    }
}
