<?php

declare(strict_types=1);

namespace App\Actions\Aux;

use Symfony\Component\Yaml\Yaml;

class Config
{
    public function __construct(
        private readonly string $configPath = '/../../../config.yml'
    )
    {
    }

    /**
     * @return mixed
     */
    public function getConfig(string $name): mixed
    {
        $configFilePath = __DIR__ . $this->configPath;

        return Yaml::parseFile($configFilePath)[$name];
    }
}