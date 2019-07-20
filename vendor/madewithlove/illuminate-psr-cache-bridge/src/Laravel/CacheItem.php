<?php
namespace Madewithlove\IlluminatePsrCacheBridge\Laravel;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed|null
     */
    private $value;

    /**
     * @var bool
     */
    private $hit;

    /**
     * @var \DateTimeInterface
     */
    private $expires;

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $hit
     */
    public function __construct($key, $value = null, $hit = false)
    {
        $this->key = $key;
        $this->hit = boolval($hit);
        $this->value = $this->hit ? $value : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function isHit()
    {
        return $this->hit;
    }

    /**
     * {@inheritdoc}
     */
    public function set($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAt($expires)
    {
        if ($expires instanceof DateTimeInterface && ! $expires instanceof DateTimeImmutable) {
            $timezone = $expires->getTimezone();
            $expires = DateTimeImmutable::createFromFormat('U', (string) $expires->getTimestamp(), $timezone);
            $expires->setTimezone($timezone);
        }

        $this->expires = $expires;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAfter($time)
    {
        if ($time === null) {
            $this->expires = null;

            return;
        }

        $this->expires = new DateTimeImmutable();

        if (! $time instanceof DateInterval) {
            $time = new DateInterval(sprintf('PT%sS', $time));
        }

        $this->expires = $this->expires->add($time);

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpiresAt()
    {
        return $this->expires;
    }
}
