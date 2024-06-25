<?php

declare(strict_types=1);

namespace Tests\Actions\Aux;

use App\Actions\Aux\InfoxToResomaq;
use App\Actions\Aux\InfoxToResomaqFile;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InfoxToResomaqFileTest extends TestCase
{
    private InfoxToResomaqFile $infoxToResomaqFile;

    protected function setUp(): void
    {
        $this->infoxToResomaqFile = new InfoxToResomaqFile(new InfoxToResomaq());
    }

    #[DataProvider('provider')]
    public function testExecute(\SplFileObject $input, string $output): void
    {
        // act
        $actual = $this->infoxToResomaqFile->execute($input);

        // assert
        $this->assertSame($output, $actual);
    }

    public static function provider(): array
    {
        return [
            [
                new \SplFileObject(__DIR__ . '/../../artifacts/input.txt', 'rb'),
                <<<EOT
                000000620240509509041Redecompras HC 2.1
                100000293209765064271XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXX 000000000000000000000000                              XXXXXXXXXXX@XXXXXXXXX.XXX                                   XXXXXXXXXXXXXXXXXXXXXXXX, 9999                    XXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXX XX02H000002808453455
                9000006
                EOT
            ],
            [
                new \SplFileObject(__DIR__ . '/../../artifacts/input_with_blank_lines.txt', 'rb'),
                <<<EOT
                000000620240509509041Redecompras HC 2.1
                100000293209765064271XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXX 000000000000000000000000                              XXXXXXXXXXX@XXXXXXXXX.XXX                                   XXXXXXXXXXXXXXXXXXXXXXXX, 9999                    XXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXX XX02H000002808453455
                9000006
                EOT
            ]
        ];
    }
}