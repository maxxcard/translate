<?php

declare(strict_types=1);

namespace App\Utils;

use Crunz\Event;
use Crunz\Schedule;

class Task
{
    public readonly Schedule $schedule;

    private function __construct(private readonly string $taskClass)
    {
        $this->schedule = new Schedule();
    }

    public static function createTask(string $taskClass): self
    {
        return new self($taskClass);
    }

    public function task(): Event
    {
        return $this->schedule->run(PHP_BINARY . ' ' . __DIR__ . '/../../index.php', [$this->taskClass]);
    }
}