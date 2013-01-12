<?php

namespace Xi\Tests\Filelib;

use Xi\Tests\Filelib\TestCase;
use Xi\Filelib\IdentityMap\CachedIdentityMap;
use Xi\Filelib\IdentityMap\Identifiable;
use Xi\Filelib\File\Resource;
use Xi\Filelib\File\File;
use Xi\Filelib\Folder\Folder;
use Xi\Filelib\Event\IdentifiableEvent;
use ArrayIterator;

class CachedIdentityMapTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $innerIm;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ed;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $cache;

    /**
     * @var CachedIdentityMap
     */
    protected $im;

    public function setUp()
    {
        $this->ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->innerIm = $this
            ->getMockBuilder('Xi\Filelib\IdentityMap\IdentityMap')
            ->setConstructorArgs(array($this->ed))
            ->setMethods(array('add', 'remove', 'removeMany', 'addMany', 'has', 'get'))
            ->getMock();

        $this->cache = $this->getMock('Xi\Filelib\IdentityMap\Cache\CacheInterface');

        $this->im = new CachedIdentityMap($this->innerIm, $this->cache);
    }

    /**
     * @test
     */
    public function implementsIdentityMapInterface()
    {
        $this->assertTrue(class_exists('Xi\Filelib\IdentityMap\CachedIdentityMap'));
        $this->assertTrue(
            is_subclass_of(
                'Xi\Filelib\IdentityMap\CachedIdentityMap',
                'Xi\Filelib\IdentityMap\IdentityMapInterface'
            )
        );
    }

    /**
     * @test
     */
    public function implementsEventSubscriberInterface()
    {
        $this->assertContains(
            'Symfony\Component\EventDispatcher\EventSubscriberInterface',
            class_implements('Xi\Filelib\IdentityMap\CachedIdentityMap')
        );
    }

    /**
     * @test
     */
    public function constructorSubscribesToEvents()
    {
        $this->ed
            ->expects($this->once())->method('addSubscriber')
            ->with(
                $this->isInstanceOf('Xi\Filelib\IdentityMap\CachedIdentityMap')
            );

        $im = new CachedIdentityMap($this->innerIm, $this->cache);
    }

    /**
     * @return array
     */
    public function provideAddableObjects()
    {
        return array(
            array(File::create(array('id' => 1))),
            array(Resource::create(array('id' => 'xooxo'))),
            array(Folder::create(array('id' => 665))),
        );
    }

    /**
     * @test
     */
    public function subscribesToCorrectEvents()
    {
        $this->assertEquals(
            array(
                'xi_filelib.file.update',
                'xi_filelib.folder.update',
                'xi_filelib.identitymap.after_add',
                'xi_filelib.identitymap.after_remove'

            ),
            array_keys(CachedIdentityMap::getSubscribedEvents())
        );
    }

    /**
     * @test
     */
    public function onSetSetsToCache()
    {
        $identifiable = $this->getMock('Xi\Filelib\IdentityMap\Identifiable');
        $event = new IdentifiableEvent($identifiable);

        $this->cache
            ->expects($this->once())
            ->method('set')
            ->with($identifiable);

        $this->im->onSet($event);
    }

    /**
     * @test
     */
    public function onRemoveRemovesFromCache()
    {
        $identifiable = $this->getMock('Xi\Filelib\IdentityMap\Identifiable');
        $event = new IdentifiableEvent($identifiable);

        $this->cache
            ->expects($this->once())
            ->method('remove')
            ->with($identifiable);
        $this->im->onRemove($event);
    }

    /**
     * @test
     */
    public function removeDelegates()
    {
        $identifiable = $this->getMock('Xi\Filelib\IdentityMap\Identifiable');

        $this->innerIm
            ->expects($this->once())
            ->method('remove')
            ->with($identifiable);

        $this->im->remove($identifiable);
    }

    /**
     * @test
     */
    public function addDelegates()
    {
        $identifiable = $this->getMock('Xi\Filelib\IdentityMap\Identifiable');

        $this->innerIm
            ->expects($this->once())
            ->method('add')
            ->with($identifiable);

        $this->im->add($identifiable);
    }

    /**
     * @test
     */
    public function hasDelegates()
    {
        $identifiable = $this->getMock('Xi\Filelib\IdentityMap\Identifiable');

        $this->innerIm
            ->expects($this->once())
            ->method('has')
            ->with($identifiable);

        $this->im->has($identifiable);
    }

    /**
     * @test
     */
    public function getDelegatesAndReturnsWhenFound()
    {
        $identifiable = $this->getMock('Xi\Filelib\IdentityMap\Identifiable');

        $this->innerIm
            ->expects($this->once())
            ->method('get')
            ->with(666, 'Xi\Filelib\File\File')
            ->will($this->returnValue($identifiable));

        $ret = $this->im->get(666, 'Xi\Filelib\File\File');
        $this->assertSame($identifiable, $ret);
    }

    /**
     * @test
     */
    public function getTriesGetFromCacheWhenNotFound()
    {
        $this->innerIm
            ->expects($this->once())
            ->method('get')
            ->with(666, 'Xi\Filelib\File\File')
            ->will($this->returnValue(false));

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with(666, 'Xi\Filelib\File\File')
            ->will($this->returnValue(false));

        $ret = $this->im->get(666, 'Xi\Filelib\File\File');
        $this->assertFalse($ret);
    }

    /**
     * @test
     */
    public function getGetsFromCacheAndSetsToIdentityMapWhenNotFound()
    {
        $identifiable = $this->getMock('Xi\Filelib\IdentityMap\Identifiable');
        $this->innerIm
            ->expects($this->once())
            ->method('get')
            ->with(666, 'Xi\Filelib\File\File')
            ->will($this->returnValue(false));

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with(666, 'Xi\Filelib\File\File')
            ->will($this->returnValue($identifiable));

        $this->innerIm
            ->expects($this->once())
            ->method('add')
            ->with($identifiable);

        $ret = $this->im->get(666, 'Xi\Filelib\File\File');
        $this->assertSame($identifiable, $ret);
    }


    /**
     * @test
     */
    public function removeManyDelegates()
    {
        $iter = new ArrayIterator(array());

        $this->innerIm
            ->expects($this->once())
            ->method('removeMany')
            ->with($iter);

        $this->im->removeMany($iter);
    }

    /**
     * @test
     */
    public function addManyDelegates()
    {
        $iter = new ArrayIterator(array());

        $this->innerIm
            ->expects($this->once())
            ->method('addMany')
            ->with($iter);

        $this->im->addMany($iter);
    }
}
