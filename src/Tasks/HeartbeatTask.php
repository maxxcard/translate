<?php

namespace App\Tasks;

use App\Actions\Heartbeat;
use App\Utils\Task;

$task = Task::createTask(Heartbeat::class);
$task->task()->everyMinute();
return $task->schedule;
