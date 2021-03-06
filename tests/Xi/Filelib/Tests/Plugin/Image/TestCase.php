<?php

/**
 * This file is part of the Xi Filelib package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Filelib\Tests\Plugin\Image;

use Imagick;

/**
 * @group plugin
 */
class TestCase extends \Xi\Filelib\Tests\TestCase
{
    public function setUp()
    {
        if (!class_exists('Imagick')) {
            $this->markTestSkipped('ImageMagick extension not loaded');
        }
    }

    public function tearDown()
    {
        if (!class_exists('Imagick')) {
            $this->markTestSkipped('ImageMagick extension not loaded');
        }
    }
}
