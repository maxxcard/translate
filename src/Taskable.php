<?php

namespace App;

interface Taskable
{
    public function execute(): void;
}