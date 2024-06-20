<?php

use App\Utils\Runner;
use DI\Container;

require_once __DIR__ . '/vendor/autoload.php';

$path = $argv[1];

$di = new Container();

$runner  = $di->get(Runner::class);

$runner->execute($path);