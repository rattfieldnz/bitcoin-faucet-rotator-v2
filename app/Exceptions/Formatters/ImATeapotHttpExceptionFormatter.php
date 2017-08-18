<?php

namespace App\Exceptions\Formatters;

use Exception;
use Illuminate\Http\JsonResponse;
use Optimus\Heimdal\Formatters\HttpExceptionFormatter;

class ImATeapotHttpExceptionFormatter extends HttpExceptionFormatter
{
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        parent::format($response, $e, $reporterResponses);

        $response->setData([
            'coffe_brewer' => 'http://ghk.h-cdn.co/assets/cm/15/11/320x320/55009368877e1-ghk-hamilton-beach-5-cup-coffeemaker-48136-s2.jpg',
            'teapot' => 'http://www.ikea.com/PIAimages/0282097_PE420125_S5.JPG'
        ]);

        return $response;
    }
}
