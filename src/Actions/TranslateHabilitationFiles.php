<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Aux\Config;
use App\Actions\Aux\InfoxToResomaqFile;
use App\Taskable;
use App\Utils\DirectoryIteratorFactory;

readonly class TranslateHabilitationFiles implements Taskable
{
    public function __construct(
        private InfoxToResomaqFile       $infoxToResomaqFile,
        private Config                   $config,
        private DirectoryIteratorFactory $directoryIteratorFactory,
    ) {
    }

    public function execute(): void
    {
        $paths = $this->config->getConfig('directories');
        $outputPath = $this->config->getConfig('output');
        foreach ($paths as $path) {
            $listOfFilesOnPath = $this->listFiles($path);
            array_map(
                fn (\SplFileObject $file) => $this->createTranslatedFile($file, $outputPath),
                $listOfFilesOnPath
            );
        }
    }

    /**
     * @param string $dir
     * @return array<int, \SplFileObject>
     */
    private function listFiles(string $dir): array
    {
        $iterator = $this->directoryIteratorFactory->instantiate($dir);
        /** @var array<int, \SplFileObject> $files */
        $files = [];
        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $files[] = $file->openFile('rb');
        }

        return $files;
    }

    private function createTranslatedFile(\SplFileObject $file, string $outputPath): void
    {
        $fileName = $this->resomaqFileName($file->getFilename());
        $output = $this->infoxToResomaqFile->execute($file);
        $newFile = new \SplFileObject(sprintf('%s/%s', $outputPath, $fileName), 'w');
        $newFile->fwrite($output);
    }

    private function resomaqFileName(string $fileName): string
    {
        // INFOX-HAB_MAX_ENV_20240509160001
        $fileNameWithoutExtension = str_replace(['.txt', '.TXT'], '', $fileName);
        $dateAndHour = substr($fileNameWithoutExtension, 18);
        $resomaqFileName = $dateAndHour . 'HC';
        $resomaqFileName .= '636557.REM';

        return $resomaqFileName;
    }
}