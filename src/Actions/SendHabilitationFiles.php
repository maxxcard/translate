<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Aux\Config;
use App\Taskable;

class SendHabilitationFiles implements Taskable
{
    public function __construct(private readonly Config $config) {}

    public function execute(): void
    {
    }
}