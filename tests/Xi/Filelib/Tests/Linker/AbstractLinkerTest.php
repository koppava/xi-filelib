<?php

/**
 * This file is part of the Xi Filelib package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Filelib\Tests\Linker;

use Xi\Filelib\Tests\TestCase;

/**
 * @group linker
 */
class AbstractLinkerTest extends TestCase
{
    /**
     * @test
     * @group parallel
     */
    public function implementsLinker()
    {
        $this->assertContains(
            'Xi\Filelib\Linker\Linker',
            class_implements('Xi\Filelib\Linker\AbstractLinker')
        );
    }
}
