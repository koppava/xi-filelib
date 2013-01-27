<?php

/**
 * This file is part of the Xi Filelib package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Filelib\Tests\Tool\Slugifier;

class SlugifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group parallel
     */
    public function interfaceShouldExist()
    {
        $this->assertTrue(interface_exists('Xi\Filelib\Tool\Slugifier\Slugifier'));
    }
}
