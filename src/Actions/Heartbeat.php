<?php

declare(strict_types=1);

namespace App\Actions;

use App\Taskable;
use App\Utils\Logger;

class Heartbeat implements Taskable
{
    public function __construct(private readonly Logger $logger)
    {
    }
    public function execute(): void
    {
        echo "Heartbeat\n";
    }
}