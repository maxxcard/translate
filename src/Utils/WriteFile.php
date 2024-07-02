<?php

declare(strict_types=1);

namespace App\Utils;

class WriteFile
{
    public function write(string $path, string $content): void
    {
        $file = new \SplFileObject($path, 'w');
        $file->fwrite($content);
    }
}