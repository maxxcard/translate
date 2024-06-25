<?php

declare(strict_types=1);

namespace Tests\Utils;

use App\Taskable;
use App\Utils\Task;
use Crunz\Event;
use Crunz\Schedule;
use Monolog\Test\TestCase;

class TaskTest extends TestCase
{
    public function testTask(): void
    {
        // arrange
        $t = $this->createMock(Taskable::class);

        // act
        $task = Task::createTask($t::class);
        $event = $task->task();
        $schedule = $task->schedule;

        // assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertInstanceOf(Event::class, $event);
        $this->assertInstanceOf(Schedule::class, $schedule);
    }
}