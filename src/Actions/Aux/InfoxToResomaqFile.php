<?php

declare(strict_types=1);

namespace App\Actions\Aux;

class InfoxToResomaqFile
{
    public function __construct(private readonly InfoxToResomaq $infoxToResomaq)
    {
    }

    public function execute(\SplFileObject $fileInput): string
    {
        /** @var array<int, string> $lines */
        $lines = [];
        $output = '';
        while (!$fileInput->eof()) {
            $line = $fileInput->fgets();
            if (trim($line) === '') {
                continue;
            }

            $lines[] = $line;
        }

        $lastLineIndex = count($lines) - 1;
        foreach ($lines as $index => $line) {
            if ($index === 0) {
                $output .= $this->infoxToResomaq->head($line) . PHP_EOL;
                continue;
            }

            if ($index === $lastLineIndex) {
                $output .= $this->infoxToResomaq->footer();
                continue;
            }

            $output .= $this->infoxToResomaq->bodyLine($line) . PHP_EOL;
        }

        return $output;
    }
}