<?php

declare(strict_types=1);

namespace Tests\Actions\Aux;

use App\Actions\Aux\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private Config $config;

    protected function setUp(): void
    {
        $this->config = new Config('/../../../tests/artifacts/config.yml');
    }

    public function testGetStringConfig(): void
    {
        // act
        $actual = $this->config->getConfig('name');

        // assert
        $this->assertSame('test', $actual);
    }

    public function testGetArrayConfig(): void
    {
        // act
        $actual = $this->config->getConfig('list');

        // assert
        $this->assertSame(['item1', 'item2', 'item3'], $actual);
    }

    public function testWithDestinationMatrix(): void
    {
        // act
        $actual = $this->config->getConfig('directory');

        // assert
        $this->assertSame(
            ['item1' => 'item2', 'item3' => 'item4'],
            $actual
        );
    }
}