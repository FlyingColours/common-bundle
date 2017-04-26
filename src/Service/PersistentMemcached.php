<?php

namespace FlyingColours\CommonBundle\Service;

/**
 * @see https://gist.github.com/K-Phoen/4327229#gistcomment-1297369
 * @see https://gist.github.com/benr77/258e42642b4632d5a826#file-memcachedwrapper-php
 */
class PersistentMemcached extends \Memcached
{
    public function addServers(array $servers)
    {
        if (0 == count($this->getServerList()))
        {
            return parent::addServers($servers);
        }

        return false;
    }

    public function addServer($host, $port, $weight = 0)
    {
        foreach ($this->getServerList() as $server)
        {
            if ($server['host'] == $host && $server['port'] == $port)
            {
                return false;
            }
        }

        return parent::addServer($host, $port, $weight);
    }
}
