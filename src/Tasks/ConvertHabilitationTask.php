<?php

namespace App\Tasks;

use App\Actions\ConvertHabilitationFiles;
use App\Utils\Task;

$task = Task::createTask(ConvertHabilitationFiles::class);
$task->task()->everyMinute();
return $task->schedule;