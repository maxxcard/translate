<?php

declare(strict_types=1);

namespace App\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;

class Sftp
{
    /**
     * @throws FilesystemException
     */
    public function move(string $localFilepath, string $remoteFilepath): void
    {
        $this->filesystem()->move($localFilepath, $remoteFilepath);
    }

    private function filesystem(): Filesystem
    {
        return new Filesystem($this->adapter());
    }

    private function adapter()
    {
       return new SftpAdapter($this->provider(), 'root', $this->converter());
    }

    private function provider(): SftpConnectionProvider
    {
        return new SftpConnectionProvider(
            host: 'localhost',
            username: 'foo',
            password: 'pass',
            port: 2222,
        );
    }

    private function converter(): PortableVisibilityConverter
    {
        return PortableVisibilityConverter::fromArray([
            'file' => [
                'public' => 0640,
                'private' => 0604,
            ],
            'dir' => [
                'public' => 0740,
                'private' => 0704,
            ]
        ]);
    }
}