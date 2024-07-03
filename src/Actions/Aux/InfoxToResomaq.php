<?php

declare(strict_types=1);

namespace App\Actions\Aux;

class InfoxToResomaq
{
    public function head(string $infoxHeadText): string
    {
        $splitInfoxString = $this->splitInfoxString($infoxHeadText);
        $head = '0000001';
        $head .= substr($splitInfoxString[1], 0, 8); // data
        $head .= '509041'; // numero do bin TODO: verificar se este bin está correto
        $head .= 'Redecompras HC 2.1';

        return $head;
    }

    public function trailer(int $numerOfRegisters): string
    {
        $numberLength = strlen((string) $numerOfRegisters); // TODO: a quantidade de linhas deve ser a quantidade total de linhas do arquivo ou a quantidade de linhas com informações para habilitação
        $text = sprintf('%s%s', str_repeat('0', 6 - $numberLength), $numerOfRegisters);

        return sprintf('9%s', $text);
    }

    public function bodyLine(string $infoxString): string
    {
        $infoxParts = $this->splitInfoxString($infoxString);

        return $this->createResomaqString($infoxParts);
    }

    /**
     * @param string $infoxString
     * @return array<int, string>
     */
    private function splitInfoxString(string $infoxString): array
    {
        return preg_split('/\s+/', $infoxString, 3);
    }

    /**
     * @param array{string, string, string} $infoxParts
     * @return string
     */
    private function createResomaqString(array $infoxParts): string
    {
        $cnpjCpf = $this->getCnpjCpf($infoxParts[1]);
        $resomaqString = sprintf('1000002%s', $cnpjCpf); // CNPJ APOS 2
        $resomaqString .= $this->addX(39) . $this->addSpace();
        $resomaqString .= $this->addX(29) . $this->addSpace();
        $resomaqString .= str_repeat('0', 24);
        $resomaqString .= $this->addSpace(30);
        $resomaqString .= sprintf('%s@%s.%s', $this->addX(11), $this->addX(9), $this->addX(3));
        $resomaqString .= $this->addSpace(35);
        $resomaqString .= sprintf('%s, 9999', $this->addX(24));
        $resomaqString .= $this->addSpace(20);
        $resomaqString .= $this->addX(29);
        $resomaqString .= $this->addSpace();
        $resomaqString .= $this->addX(29);
        $resomaqString .= $this->addSpace();
        $resomaqString .= $this->addX(2);
        $resomaqString .= $this->habitation($infoxParts[1]);

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

    private function getCnpjCpf(string $cnpjCpf): string
    {
        return substr($cnpjCpf, 33, 14);
    }

    private function habitation(string $infoxData): string
    {
        $habitation = $infoxData[56] === '1' ? 'H' : 'D';
        $cieloCode = substr($infoxData, 17, 15);
        $habitationCode = sprintf('02%s', $habitation);
        $habitationCode .= $cieloCode;

        return $habitationCode;
    }
}