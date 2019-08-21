<?php

namespace FlyingColours\CommonBundle\Service;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Intl\Exception\NotImplementedException;

class InMemoryCache implements CacheInterface
{
    private $cache = [];

    public function get($key, $default = null)
    {
        return $this->cache[$key] ?? $default;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->cache[$key] = $value;

        return true;
    }

    public function delete($key)
    {
        unset($this->cache[$key]);
    }

    public function clear()
    {
        $this->cache = [];
    }

    public function getMultiple($keys, $default = null)
    {
        throw new NotImplementedException();
    }

    public function setMultiple($values, $ttl = null)
    {
        throw new NotImplementedException();
    }

    public function deleteMultiple($keys)
    {
        throw new NotImplementedException();
    }

    public function has($key)
    {
        return isset($this->cache[$key]);
    }

}
