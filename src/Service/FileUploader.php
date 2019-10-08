<?php


namespace Deozza\PhilarmonyCoreBundle\Service;

use Deozza\PhilarmonyCoreBundle\Document\FileProperty;
use League\Flysystem\FilesystemInterface;
use Ramsey\Uuid\Uuid;

class FileUploader
{
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->fs = $filesystem;
    }

    public function persistFile(FileProperty $fileProperty)
    {
        $this->fs->write($this->getFilename($fileProperty),$fileProperty->getFile());
    }

    public function getFile(FileProperty $fileProperty)
    {
        return $this->fs->read($fileProperty->getFilename());
    }

    private function getFilename(FileProperty $fileProperty): string
    {
        $extension = explode('/', $fileProperty->getMimetype() )[1];
        $fileProperty->setFilename(Uuid::uuid4()->toString().".".$extension);
        return $fileProperty->getFilename();
    }
}