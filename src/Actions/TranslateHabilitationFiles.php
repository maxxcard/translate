<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Aux\Config;
use App\Actions\Aux\InfoxToResomaqFile;
use App\Actions\Aux\RegisterTransaction;
use App\Taskable;
use App\Utils\DirectoryIteratorFactory;
use App\Utils\WriteFile;

readonly class TranslateHabilitationFiles implements Taskable
{
    public function __construct(
        private InfoxToResomaqFile       $infoxToResomaqFile,
        private Config                   $config,
        private DirectoryIteratorFactory $directoryIteratorFactory,
        private RegisterTransaction      $registerTransaction,
        private WriteFile                $writeFile,
    )
    {
    }

    public function execute(): void
    {
        $paths = $this->config->getConfig('directories');
        foreach ($paths as $path => $destination) {
            $listOfFilesOnPath = $this->listFiles($path);
            array_map(fn(\SplFileObject $file) => $this->executeAction($file, $destination), $listOfFilesOnPath);
        }
    }

    /**
     * @return array<int, \SplFileObject>
     */
    private function listFiles(string $dir): array
    {
        $iterator = $this->directoryIteratorFactory->instantiate($dir);
        /** @var array<int, \SplFileObject> $files */
        $files = [];

        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'TXT') {
                continue;
            }

            $files[] = $file->openFile('rb');
        }

        return $files;
    }

    private function executeAction(\SplFileObject $file, string $destination): void
    {
        if ($this->registerTransaction->transactionExists($file->getFilename())) {
            return;
        }

        $createdFileContent = $this->infoxToResomaqFile->execute($file);
        $fileName = $this->resomaqFileName($file->getFilename());
        $this->writeFile->write(sprintf('%s/%s', $destination, $fileName), $createdFileContent);
        $this->registerTransaction->create($fileName, $file->getFilename());
    }

    private function resomaqFileName(string $fileName): string
    {
        $fileNameWithoutExtension = str_replace(['.txt', '.TXT'], '', $fileName);
        $dateAndHour = substr($fileNameWithoutExtension, 18);
        $resomaqFileName = $dateAndHour . 'HC';
        $resomaqFileName .= '636557.REM';

        return $resomaqFileName;
    }
}