<?php

namespace Xi\Filelib\Tests\Tool\MimeTypeResolver;

use Xi\Filelib\Tool\MimeTypeResolver\SymfonyMimeTypeResolver;

class SymfonyMimeTypeResolverTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser')) {
            $this->markTestSkipped('Symfony MimeTypeGuesser not loadable');
        }

        parent::setUp();
        $this->resolver = new SymfonyMimeTypeResolver();
    }
}
