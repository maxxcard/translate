<?php

declare(strict_types=1);

namespace App\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\Local\LocalFilesystemAdapter;

class Local
{
    /**
     * @throws FilesystemException
     */
    public function write(string $path, string $content): void
    {
        $this->filesystem()->write($path, $content);
    }

    private function filesystem(): Filesystem
    {
        $adapter = new LocalFilesystemAdapter(__DIR__);

        return new Filesystem($adapter);
    }
}