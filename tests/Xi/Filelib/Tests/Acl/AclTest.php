<?php

namespace Xi\Filelib\Tests\Acl;

class AclTest extends \Xi\Filelib\Tests\TestCase
{

    /**
     * @test
     * @group parallel
     */
    public function interfaceShouldExist()
    {
        $this->assertTrue(interface_exists('Xi\Filelib\Acl\Acl'));
    }

}
