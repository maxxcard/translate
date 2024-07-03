<?php

declare(strict_types=1);

namespace App\Utils;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonoLogger;

readonly class Logger
{
    public MonoLogger $log;
    
    public function __construct()
    {
        $this->log = new MonoLogger('converter');
        $this->log->pushHandler(new StreamHandler(__DIR__ . '/../../var/converter.log', Level::Info));
    }

    public function __call(string $name, array $arguments): void
    {
        $this->log->$name(...$arguments);
    }
}