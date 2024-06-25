<?php

declare(strict_types=1);

namespace App\Utils;

use App\Taskable;
use DI\Container;

readonly class Runner
{
    public function __construct(private Container $container, private Logger $logger) {}

    /**
     * @param class-string $toExecute
     * @return void
     */
    public function execute(string $toExecute): void
    {
        try {
            $task = $this->container->get($toExecute);

            if (!$task instanceof Taskable) {
                throw new \Exception('task must implement Taskable interface');
            }

            $task->execute();

            $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
            $this->logger->log->info(sprintf('Task of %s successfuly executed at %s', $toExecute, $now));
        } catch (\Throwable $e) {
            $this->logger->info($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}