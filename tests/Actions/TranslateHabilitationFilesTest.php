<?php

declare(strict_types=1);

namespace Tests\Actions;

use App\Actions\Aux\Config;
use App\Actions\Aux\InfoxToResomaqFile;
use App\Actions\Aux\RegisterTransaction;
use App\Actions\TranslateHabilitationFiles;
use App\Utils\DirectoryIteratorFactory;
use App\Utils\WriteFile;
use DirectoryIterator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TranslateHabilitationFilesTest extends TestCase
{
    private TranslateHabilitationFiles $translateHabilitationFiles;

    private InfoxToResomaqFile&MockObject $infoxToResomaqFileMock;

    private Config&MockObject $configMock;

    private DirectoryIteratorFactory&MockObject $directoryIteratorFactoryMock;

    private RegisterTransaction&MockObject $registerTransactionMock;

    private WriteFile&MockObject $writeFileMock;

    protected function setUp(): void
    {
        $this->translateHabilitationFiles = new TranslateHabilitationFiles(
            $this->infoxToResomaqFileMock = $this->createMock(InfoxToResomaqFile::class),
            $this->configMock = $this->createMock(Config::class),
            $this->directoryIteratorFactoryMock = $this->createMock(DirectoryIteratorFactory::class),
            $this->registerTransactionMock = $this->createMock(RegisterTransaction::class),
            $this->writeFileMock = $this->createMock(WriteFile::class)
        );
    }

    public function testExecute(): void
    {
        // arrange
        $this->configMock->expects($this->once())->method('getConfig')->willReturn(['a' => 'b']);

        $splFileMock = $this->getMockBuilder(\SplFileObject::class)
            ->setConstructorArgs(['php://memory'])
            ->getMock();

        $directoryIteratorMock = $this->createMock(DirectoryIterator::class);
        $directoryIteratorMock->expects($this->once())->method('rewind');
        $directoryIteratorMock->method('current')->willReturnSelf();
        $directoryIteratorMock->method('valid')->willReturnOnConsecutiveCalls(true, false);
        $directoryIteratorMock->expects($this->once())->method('next');
        $directoryIteratorMock->expects($this->once())->method('isFile')->willReturn(true);
        $directoryIteratorMock->expects($this->once())->method('getExtension')->willReturn('TXT');
        $directoryIteratorMock->expects($this->once())->method('openFile')->willReturn($splFileMock);

        $this->directoryIteratorFactoryMock->expects($this->once())->method('instantiate')->willReturn($directoryIteratorMock);

        $this->registerTransactionMock->expects($this->once())->method('transactionExists')->willReturn(false);

        $this->writeFileMock->expects($this->once())->method('write');

        // act
        $this->translateHabilitationFiles->execute();
    }

    public function testExecuteWithoutFiles(): void
    {
        // arrange
        $this->configMock->expects($this->once())->method('getConfig')->willReturn(['a' => 'b']);

        $splFileMock = $this->getMockBuilder(\SplFileObject::class)
            ->setConstructorArgs(['php://memory'])
            ->getMock();

        $directoryIteratorMock = $this->createMock(DirectoryIterator::class);
        $directoryIteratorMock->expects($this->once())->method('rewind');
        $directoryIteratorMock->method('current')->willReturnSelf();
        $directoryIteratorMock->method('valid')->willReturnOnConsecutiveCalls( false);

        $this->directoryIteratorFactoryMock->expects($this->once())->method('instantiate')->willReturn($directoryIteratorMock);

        // act
        $this->translateHabilitationFiles->execute();
    }
}