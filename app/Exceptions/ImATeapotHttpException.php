<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ImATeapotHttpException extends HttpException
{
    public function __construct(\Exception $previous = null, $code = 0)
    {
        parent::__construct(418, 'I\'m a teapot', $previous, [], $code);
    }
}
