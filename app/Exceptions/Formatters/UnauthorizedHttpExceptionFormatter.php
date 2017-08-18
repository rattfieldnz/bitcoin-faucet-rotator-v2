<?php

namespace App\Exceptions\Formatters;

use Exception;
use Illuminate\Http\JsonResponse;
use Optimus\Heimdal\Formatters\HttpExceptionFormatter;


class UnauthorizedHttpExceptionFormatter extends HttpExceptionFormatter
{
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        parent::format($response, $e, $reporterResponses);

        $response->headers->set('WWW-Authenticate', $e->getHeaders()['WWW-Authenticate']);

        return $response;
    }
}
