<?php

namespace App\Tasks;

use App\Actions\TranslateHabilitationFiles;
use App\Utils\Task;

$task = Task::createTask(TranslateHabilitationFiles::class);
$task->task()->everyMinute();
return $task->schedule;