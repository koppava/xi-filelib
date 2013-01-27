<?php

namespace Xi\Filelib\Tests\Event;

use Symfony\Component\EventDispatcher\Event;
use Xi\Filelib\Event\FilelibEvent;

class FilelibEventTest extends \Xi\Filelib\Tests\TestCase
{
    /**
     * @test
     * @group parallel
     */
    public function classShouldExist()
    {
        $this->assertTrue(class_exists('Xi\Filelib\Event\FilelibEvent'));
        $this->assertContains(
            'Symfony\Component\EventDispatcher\Event',
            class_parents('Xi\Filelib\Event\FilelibEvent')
        );
    }

    /**
     * @test
     * @group parallel
     */
    public function eventShouldInitializeCorrectly()
    {
        $filelib = $this->getMock('Xi\Filelib\FileLibrary');
        $event = new FilelibEvent($filelib);

        $filelib2 = $event->getFilelib();
        $this->assertSame($filelib, $filelib2);
    }
}
