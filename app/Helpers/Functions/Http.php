<?php

namespace App\Helpers\Functions;

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
    public static function canShowInIframes($url): bool {

        $headers = get_headers($url);
        $xFrameOptions = "X-Frame-Options: ";
        $contentSecurityPolicy = "Content-Security-Policy: frame-ancestors ";
        $canShow = true;

        if(count($headers) == 0){
            return false;
        }

        foreach($headers as $key => $value){
            if(substr($value, 0, strlen($xFrameOptions)) == $xFrameOptions){
                $xFrameOption = substr($value, strlen($xFrameOptions), strlen($value));
                if(
                    strtoupper($xFrameOption) == "SAMEORIGIN" || strtoupper($xFrameOption) == "DENY"
                ){
                    $canShow = false;
                }
            }
            else if(substr($value, 0, strlen($contentSecurityPolicy)) == $contentSecurityPolicy){

                $cspFrameAncestorsOption = substr($value, strlen($contentSecurityPolicy), strlen($value));

                if(strtoupper($cspFrameAncestorsOption) == "'NONE'" || strtoupper($cspFrameAncestorsOption) == "'SELF'"){
                    $canShow = false;
                }

            }
        }
        return $canShow;
    }
}