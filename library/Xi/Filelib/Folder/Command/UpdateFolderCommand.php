<?php

/**
 * This file is part of the Xi Filelib package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Filelib\Folder\Command;

use Xi\Filelib\Operator\FolderOperator;
use Xi\Filelib\Operator\FileOperator;
use Xi\Filelib\Folder\Folder;
use Xi\Filelib\Event\FolderEvent;

class UpdateFolderCommand extends AbstractFolderCommand
{
    /**
     *
     * @var FileOperator
     */
    private $fileOperator;

    /**
     *
     * @var Folder
     */
    private $folder;

    public function __construct(FolderOperator $folderOperator, FileOperator $fileOperator, Folder $folder)
    {
        parent::__construct($folderOperator);
        $this->fileOperator = $fileOperator;
        $this->folder = $folder;
    }

    public function execute()
    {
        $route = $this->folderOperator->buildRoute($this->folder);
        $this->folder->setUrl($route);

        $this->folderOperator->getBackend()->updateFolder($this->folder);

        foreach ($this->folderOperator->findFiles($this->folder) as $file) {
            $command = $this->folderOperator->createCommand('Xi\Filelib\File\Command\UpdateFileCommand', array(
                $this->fileOperator,
                $file
            ));
            $command->execute();
        }

        foreach ($this->folderOperator->findSubFolders($this->folder) as $subFolder) {
            $command = $this->folderOperator->createCommand('Xi\Filelib\Folder\Command\UpdateFolderCommand', array(
                $this->folderOperator,
                $this->fileOperator,
                $subFolder
            ));
            $command->execute();
        }

        $event = new FolderEvent($this->folder);
        $this->folderOperator->getEventDispatcher()->dispatch(
            'xi_filelib.folder.update',
            $event
        );
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->folder = $data['folder'];
        $this->uuid = $data['uuid'];
    }

    public function serialize()
    {
        return serialize(array(
            'folder' => $this->folder,
            'uuid' => $this->uuid,
        ));

    }
}
