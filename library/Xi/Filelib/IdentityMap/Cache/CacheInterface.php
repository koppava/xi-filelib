<?php

namespace Xi\Filelib\IdentityMap\Cache;

use Xi\Filelib\IdentityMap\Identifiable;

interface CacheInterface
{
    /**
     * Gets an identifiable by id and class name
     *
     * @param mixed $id
     * @param string $className
     * @return Identifiable|false
     */
    public function get($id, $className);

    /**
     * Sets an identifiable
     *
     * @param Identifiable $object
     * @return bool
     */
    public function set(Identifiable $object);

    /**
     * Removes an identifiable
     *
     * @param Identifiable $object
     * @return bool
     */
    public function remove(Identifiable $object);

}
