<?php
/**
 * Ok, glad you are here
 * first we get a config instance, and set the settings
 * $config = HTMLPurifier_Config::createDefault();
 * $config->set('Core.Encoding', $this->config->get('purifier.encoding'));
 * $config->set('Cache.SerializerPath', $this->config->get('purifier.cachePath'));
 * if ( ! $this->config->get('purifier.finalize')) {
 *     $config->autoFinalize = false;
 * }
 * $config->loadArray($this->getConfig());
 *
 * You must NOT delete the default settings
 * anything in settings should be compacted with params that needed to instance HTMLPurifier_Config.
 *
 * @link http://htmlpurifier.org/live/configdoc/plain.html
 */

return [
    'encoding'      => 'UTF-8',
    'finalize'      => true,
    'cachePath'     => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    "settings" => [
        "default" => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => 'div,b,strong,i,em,a[href|class|title|target],ul,ol,li'
                .',p[style],br,span[style],img[width|height|alt|src],'
                . 'iframe[src|scrolling|style],h1,h2,h3,h4,h5,h6,'
                . 'dt,dl',
            "HTML.SafeIframe" => 'true',
            "URI.SafeIframeRegexp" => "%^(http://|https://|//)(mellowads.com|coinurl.com|a-ads.com|ad.a-ads.com|bee-ads.com)%",
        ],
        "generalFields" => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => '',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.Linkify' => false,
        ]
    ],

];
