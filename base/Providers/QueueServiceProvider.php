<?php

namespace Base\Providers;

use Base\Commands\QueueWorkCommand;
use Base\Core\Container;
use Base\Interfaces\ProviderInterface;

class QueueServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        // Placeholder for queue jobs registration
        // Example: $container->bind(JobQueueInterface::class, fn() => new JobQueue());
        $container->bind(QueueWorkCommand::class, function () {
            return new QueueWorkCommand();
        });
    }
}
