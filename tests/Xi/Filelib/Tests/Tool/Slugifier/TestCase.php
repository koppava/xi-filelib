<?php

namespace Xi\Filelib\Tests\Tool\Slugifier;

use \Xi\Filelib\Tool\Slugifier;

class TestCase extends \Xi\Filelib\Tests\TestCase
{
    /**
     *
     * @var Slugifier
     */
    protected $slugifier;

    /**
     * @test
     * @group parallel
     */
    public function slugifierShouldSlugifySimpleNonUnicodeStringsProperly()
    {
        $this->assertEquals('peksu-con', $this->slugifier->slugify('peksu con'));
        $this->assertEquals('lussuti-lussuti', $this->slugifier->slugify('lussuti_lussuti'));
        $this->assertEquals('suuren-ugrilaisen-kansan-sielu', $this->slugifier->slugify('SUUrEN ugRILAIseN kanSAn SIELU'));
    }

    /**
     * @test
     * @group parallel
     */
    public function slugifyPathShouldSlugifyAllPartsOfAPath()
    {
        $this->assertEquals('suuren/ugrilaisen/kansan/sielu', $this->slugifier->slugifyPath('suuren/ugrilaisen/kansan/sielu'));
    }

}
