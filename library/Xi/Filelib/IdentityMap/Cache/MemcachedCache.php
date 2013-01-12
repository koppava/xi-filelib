<?php

namespace Xi\Filelib\IdentityMap\Cache;

use Xi\Filelib\IdentityMap\Identifiable;
use Memcached;

class MemcachedCache implements CacheInterface
{
    /**
     * @var \Memcached
     */
    private $memcached;

    /**
     * @var string
     */
    private $prefix;

    public function __construct(Memcached $memcached, $prefix = 'xi_filelib')
    {
        $this->memcached = $memcached;
        $this->prefix = $prefix;
    }

    /**
     * Gets an identifiable by id and class name
     *
     * @param mixed $id
     * @param string $className
     * @return Identifiable|false
     */
    public function get($id, $className)
    {
        $ret = $this->memcached->get($this->getIdentifier($id, $className));
        return $ret ?: false;
    }

    /**
     * Sets an identifiable
     *
     * @param Identifiable $object
     * @return bool
     */
    public function set(Identifiable $object)
    {
        return $this->memcached->set($this->getIdentifierFromObject($object), $object);
    }

    /**
     * Removes an identifiable
     *
     * @param Identifiable $object
     * @return bool
     */
    public function remove(Identifiable $object)
    {
        return $this->memcached->delete($this->getIdentifierFromObject($object));
    }

    /**
     * @param $id
     * @param $className
     * @return string
     */
    private function getIdentifier($id, $className)
    {
        return $className . ' ' . $id;
    }

    /**
     * @param Identifiable $object
     * @return string
     */
    private function getIdentifierFromObject(Identifiable $object)
    {
        return $this->getIdentifier($object->getId(), get_class($object));
    }
}
