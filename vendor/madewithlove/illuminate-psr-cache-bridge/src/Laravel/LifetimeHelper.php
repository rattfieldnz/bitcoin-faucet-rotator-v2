<?php
namespace Madewithlove\IlluminatePsrCacheBridge\Laravel;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Illuminate\Contracts\Cache\Store;
use ReflectionClass;

class LifetimeHelper
{
    public static function computeLifetime(DateTimeInterface $expiresAt)
    {
        $now = new DateTimeImmutable('now', $expiresAt->getTimezone());

        $seconds = $expiresAt->getTimestamp() - $now->getTimestamp();

        return self::isLegacy() ? (int) floor($seconds / 60.0) : $seconds;
    }

    private static function isLegacy()
    {
        static $legacy;

        if ($legacy === null) {
            $params = (new ReflectionClass(Store::class))->getMethod('put')->getParameters();
            $legacy = $params[2]->getName() === 'minutes';
        }

        return $legacy;
    }
}
