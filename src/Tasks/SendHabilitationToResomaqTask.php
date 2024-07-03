<?php

declare(strict_types=1);

use App\Actions\SendHabilitationFiles;
use App\Utils\Task;

$task = Task::createTask(SendHabilitationFiles::class);
$task->task()->everyMinute();
return $task->schedule;
