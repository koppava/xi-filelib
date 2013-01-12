<?php

/**
 * This file is part of the Xi Filelib package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Filelib\IdentityMap;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Xi\Filelib\Event\IdentifiableEvent;
use Xi\Filelib\IdentityMap\Cache\CacheInterface;
use Iterator;

/**
 * Identity map
 */
class CachedIdentityMap implements EventSubscriberInterface, IdentityMapInterface
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * @var array
     */
    private $objects = array();

    /**
     * @param IdentityMap $identityMap
     * @param CacheInterface $cache
     */
    public function __construct(IdentityMap $identityMap, CacheInterface $cache)
    {
        $eventDispatcher = $identityMap->getEventDispatcher();
        $eventDispatcher->addSubscriber($this);
        $this->cache = $cache;
        $this->identityMap = $identityMap;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'xi_filelib.file.update' => 'onSet',
            'xi_filelib.folder.update' => 'onSet',
            'xi_filelib.identitymap.after_add' => 'onSet',
            'xi_filelib.identitymap.after_remove' => 'onRemove',
        );
    }

    /**
     * Returns whether identity map has an identifiable
     *
     * @param Identifiable $object
     * @return bool
     */
    public function has(Identifiable $object)
    {
        return $this->identityMap->has($object);
    }

    /**
     * Adds an identifiable to identity map
     *
     * @param Identifiable $object
     * @throws IdentityMapException
     * @return bool
     */
    public function add(Identifiable $object)
    {
        return $this->identityMap->add($object);
    }

    /**
     * Adds many identifiables to identity map
     *
     * @param Iterator $iterator
     */
    public function addMany(Iterator $iterator)
    {
        $this->identityMap->addMany($iterator);
    }

    /**
     * Removes many identifiables from identity map
     *
     * @param Iterator $iterator
     */
    public function removeMany(Iterator $iterator)
    {
        $this->identityMap->removeMany($iterator);
    }
    /**
     * Removes an identifiable
     *
     * @param Identifiable $object
     * @return bool
     */
    public function remove(Identifiable $object)
    {
        return $this->identityMap->remove($object);
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
        $ret = $this->identityMap->get($id, $className) ?: $this->getFromCache($id, $className);
        return $ret;
    }

    /**
     * @param IdentifiableEvent $event
     */
    public function onSet(IdentifiableEvent $event)
    {
        $this->cache->set($event->getIdentifiable());
    }

    /**
     * @param IdentifiableEvent $event
     */
    public function onRemove(IdentifiableEvent $event)
    {
        $this->cache->remove($event->getIdentifiable());
    }

    /**
     * @param $id
     * @param $className
     * @return Identifiable|false
     */
    protected function getFromCache($id, $className)
    {
        $ret = $this->cache->get($id, $className);
        if ($ret) {
            $this->add($ret);
        }
        return $ret;
    }
}
