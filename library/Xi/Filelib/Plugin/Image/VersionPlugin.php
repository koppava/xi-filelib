<?php

/**
 * This file is part of the Xi Filelib package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Filelib\Plugin\Image;

use Xi\Filelib\Configurator;
use Xi\Filelib\File\File;
use Xi\Filelib\File\FileOperator;
use Xi\Filelib\Plugin\VersionProvider\AbstractVersionProvider;

/**
 * Versions an image
 */
class VersionPlugin extends AbstractVersionProvider
{
    protected $providesFor = array('image');

    protected $imageMagickHelper;

    /**
     * @var File extension for the version
     */
    protected $extension;

    /**
     * @var string
     */
    private $tempDir;

    /**
     * @var array
     */
    private $imageMagickConfig;

    public function __construct(
        $identifier,
        $tempDir,
        $extension,
        $imageMagickOptions
    ) {
        parent::__construct($identifier);
        $this->tempDir = $tempDir;
        $this->extension = $extension;
        $this->imageMagickOptions = $imageMagickOptions;
    }

    /**
     * Returns ImageMagick helper
     *
     * @return ImageMagickHelper
     */
    public function getImageMagickHelper()
    {
        if (!$this->imageMagickHelper) {
            $this->imageMagickHelper = new ImageMagickHelper();

            // @todo: Fucktor away
            Configurator::setOptions($this->imageMagickHelper, $this->imageMagickOptions);
        }

        return $this->imageMagickHelper;
    }

    /**
     * Creates and stores version
     *
     * @param  File  $file
     * @return array
     */
    public function createVersions(File $file)
    {
        // Todo: optimize
        $retrieved = $this->getStorage()->retrieve($file->getResource())->getPathname();
        $img = $this->getImageMagickHelper()->createImagick($retrieved);

        $this->getImageMagickHelper()->execute($img);

        $tmp = $this->tempDir . '/' . uniqid('', true);
        $img->writeImage($tmp);

        return array($this->getIdentifier() => $tmp);
    }

    public function getVersions()
    {
        return array($this->identifier);
    }

    /**
     * Sets file extension
     *
     * @param  string          $extension File extension
     * @return VersionProvider
     */
    public function setExtension($extension)
    {
        $extension = str_replace('.', '', $extension);
        $this->extension = $extension;

        return $this;
    }

    /**
     * Returns the plugins file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    public function getExtensionFor($version)
    {
        return $this->getExtension();
    }

    /**
     * @return string
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }

    public function isSharedResourceAllowed()
    {
        return true;
    }

    public function areSharedVersionsAllowed()
    {
        return true;
    }
}
