<?php

declare(strict_types=1);

namespace App\Actions;

use App\Taskable;
use App\Utils\Logger;

class InfoxToResomaq
{
    public function execute(string $infoxString): string
    {
        $infoxParts = $this->splitInfoxString($infoxString);

        return $this->createResomaqString($infoxString);
    }

    private function splitInfoxString(string $infoxString): array
    {
        return preg_split('/\s+/', $infoxString, 3);
    }

    private function createResomaqString(string $infoxString): string
    {
        $resomaqString = sprintf('1000002%s ', '');
        $resomaqString .= $this->addX(29) . ' ';
        $resomaqString .= 'telefone';
        $resomaqString .= $this->addSpace(31);
        $resomaqString .= sprintf('%s@%s.%s', $this->addX(11), $this->addX(9), $this->addX(3));
        $resomaqString .= $this->addSpace(36);
        $resomaqString .= sprintf('%s, 999', $this->addX(24));
        $resomaqString .= $this->addSpace(21);
        $resomaqString .= $this->addX(29);
        $resomaqString .= $this->addSpace();
        $resomaqString .= $this->addX(29);
        $resomaqString .= $this->addSpace();
        $resomaqString .= 'coisa';

        return $resomaqString;
    }

    private function addX(int $number = 1): string
    {
        return str_repeat('X', $number);
    }

    private function addSpace(int $number = 1): string
    {
        return str_repeat(' ', $number);
    }
}