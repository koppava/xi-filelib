<?php

namespace Xi\Filelib\Tests\Tool\MimeTypeResolver;

use Xi\Filelib\Tests\TestCase as FilelibTestCase;

class MimeTypeResolverTest extends FilelibTestCase
{
    /**
     * @test
     * @group parallel
     */
    public function interfaceShouldExist()
    {
        $this->assertTrue(interface_exists('Xi\Filelib\Tool\MimeTypeResolver\MimeTypeResolver'));
    }

}
