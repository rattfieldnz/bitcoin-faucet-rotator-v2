<?php

require './vendor/autoload.php';

$pattern = '(http://thebitcoinrotator.192.168.22.10.xip.io|freebtc.192.168.22.10.xip.io)';

$replace = Config::get('app.url');

$larouteJS = './resources/assets/js/laroute/laroute.js';
$mainScripts = './public/assets/js/mainScripts.js';
$mainScriptsMin = './public/assets/js/mainScripts.min.js';

try {
    $larouteResources = file_get_contents($larouteJS);
    $laroutePublic = file_get_contents($mainScripts);
    $laroutePublicMin = file_get_contents($mainScriptsMin);

    $larouteResourcesReplaced = preg_replace($pattern, $replace, $larouteResources);
    file_put_contents($larouteJS, $larouteResourcesReplaced);

    $laroutePublicReplaced = preg_replace($pattern, $replace, $laroutePublic);
    file_put_contents($mainScripts, $laroutePublicReplaced);

    $laroutePublicMinReplaced = preg_replace($pattern, $replace, $laroutePublicMin);
    file_put_contents($mainScriptsMin, $laroutePublicMinReplaced);

    echo "\nCustom script has successfully completed!\n\n";
} catch(Exception $e){
    echo "Line " . $e->getLine() . ": " . $e->getMessage();
}
