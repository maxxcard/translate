<?php

declare(strict_types=1);

namespace Tests\Actions\Aux;

use App\Actions\Aux\InfoxToResomaq;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InfoxToResomaqTest extends TestCase
{
    private InfoxToResomaq $infoxToResomaq;

    protected function setUp(): void
    {
        $this->infoxToResomaq = new InfoxToResomaq();
    }

    #[DataProvider('provider')]
    public function testExecute(string $line, string $expected): void
    {
        // act
        $actual = $this->infoxToResomaq->bodyLine($line);

        // assert
        $this->assertSame($expected, $actual);
    }

    public static function provider(): array
    {
        return [
            [
                '1740000000000                 20240509000001866000002808453455193209765064271V019600001                                                                000000001038451',
                '100000293209765064271XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXX 000000000000000000000000                              XXXXXXXXXXX@XXXXXXXXX.XXX                                   XXXXXXXXXXXXXXXXXXXXXXXX, 9999                    XXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXX XX02H000002808453455'
            ]
        ];
    }
}