<?php

namespace App\Helpers\Functions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

/**
 * Class Http
 *
 * A class to manage HTTP-related functionality.
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 *
 * @package App\Helpers\Functions
 */
class Http
{
    /**
     * Check if a given URL can be displayed in iframes.
     *
     * @param $url
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy
     *      https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     *
     * @todo Improve this code, with respect to performance when checking various number of headers for a select few.
     *
     * @return bool
     */
    public static function canShowInIframes($url): bool
    {

        $headers = @get_headers($url);
        $xFrameOptions = "X-Frame-Options: ";
        $contentSecurityPolicy = "Content-Security-Policy: frame-ancestors ";
        $canShow = true;

        if ($headers == false || count($headers) == 0) {
            return false;
        }

        foreach ($headers as $key => $value) {
            if (substr($value, 0, strlen($xFrameOptions)) == $xFrameOptions) {
                $xFrameOption = substr($value, strlen($xFrameOptions), strlen($value));
                if (strtoupper($xFrameOption) == "SAMEORIGIN" || strtoupper($xFrameOption) == "DENY"
                ) {
                    $canShow = false;
                }
            } else if (substr($value, 0, strlen($contentSecurityPolicy)) == $contentSecurityPolicy) {
                $cspFrameAncestorsOption = substr($value, strlen($contentSecurityPolicy), strlen($value));

                if (strtoupper($cspFrameAncestorsOption) == "'NONE'" || strtoupper($cspFrameAncestorsOption) == "'SELF'") {
                    $canShow = false;
                }
            }
        }
        return $canShow;
    }

    /**
     * Format an exception as a Collection.
     *
     * @param string $message
     * @param string $status
     * @param int    $code
     *
     * @return \Illuminate\Support\Collection
     */
    public static function exceptionAsCollection(string $message = 'Message not set.', string $status = 'error', int $code = 500): Collection
    {
        return collect(
            [
                'status' => $status,
                'code' => $code,
                'message' => $message
            ]
        );
    }

    /**
     * Format an exception as a Json response.
     *
     * @param string $status
     * @param int    $code
     * @param string $message
     * @param string $sentryID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function jsonException(
        string $status = 'error',
        int $code = 500,
        string $message = 'An error has occurred',
        string $sentryID
    ): JsonResponse {
    
        return response()->json(
            [
                'status' => $status,
                'code' => $code,
                'sentryID' => $sentryID,
                'message' => $message,
            ],
            $code
        );
    }

    /**
     * Get an HTTP message via specified code.
     *
     * @param int $code
     *
     * @return string
     */
    public static function getHttpMessage(int $code): string
    {
        $statuses =  self::statuses();

        return array_key_exists($code, $statuses) == true ? $statuses[$code] : "Unknown message for status code '" . $code . "'";
    }

    /**
     * Return an array of known HTTP statuses.
     *
     * @see https://gist.github.com/henriquemoody/6580488
     * @see http://getstatuscode.com
     *
     * @return array
     */
    public static function statuses(): array
    {
        return [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing', // WebDAV; RFC 2518
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information', // since HTTP/1.1
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status', // WebDAV; RFC 4918
            208 => 'Already Reported', // WebDAV; RFC 5842
            226 => 'IM Used', // RFC 3229
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other', // since HTTP/1.1
            304 => 'Not Modified',
            305 => 'Use Proxy', // since HTTP/1.1
            306 => 'Switch Proxy',
            307 => 'Temporary Redirect', // since HTTP/1.1
            308 => 'Permanent Redirect', // approved as experimental RFC
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot', // RFC 2324
            419 => 'Authentication Timeout', // not in RFC 2616
            420 => 'Enhance Your Calm (Method Failure)', // Twitter / Spring Framework
            422 => 'Unprocessable Entity', // WebDAV; RFC 4918
            423 => 'Locked', // WebDAV; RFC 4918
            424 => 'Failed Dependency / Method Failure', // WebDAV; RFC 4918
            425 => 'Unordered Collection', // Internet draft
            426 => 'Upgrade Required', // RFC 2817
            428 => 'Precondition Required', // RFC 6585
            429 => 'Too Many Requests', // RFC 6585
            431 => 'Request Header Fields Too Large', // RFC 6585
            444 => 'No Response', // Nginx
            449 => 'Retry With', // Microsoft
            450 => 'Blocked by Windows Parental Controls', // Microsoft
            451 => 'Unavailable For Legal Reasons', // Internet draft
            494 => 'Request Header Too Large', // Nginx
            495 => 'Cert Error', // Nginx
            496 => 'No Cert', // Nginx
            497 => 'HTTP to HTTPS', // Nginx
            499 => 'Client Closed Request', // Nginx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates', // RFC 2295
            507 => 'Insufficient Storage', // WebDAV; RFC 4918
            508 => 'Loop Detected', // WebDAV; RFC 5842
            509 => 'Bandwidth Limit Exceeded', // Apache bw/limited extension
            510 => 'Not Extended', // RFC 2774
            511 => 'Network Authentication Required', // RFC 6585
            598 => 'Network read timeout error', // Unknown
            599 => 'Network connect timeout error', // Unknown
        ];
    }
}
