<?php

declare(strict_types=1);

namespace App\Utils;

use DirectoryIterator;

class DirectoryIteratorFactory
{
    public function instantiate(string $directory): DirectoryIterator
    {
        return new DirectoryIterator($directory);
    }
}