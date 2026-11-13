<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Stub;

use Psr\Log\AbstractLogger;
use Stringable;

class SpyLogger extends AbstractLogger
{
    /**
     * @var array<int, array{level: mixed, message: string}>
     */
    public array $records = [];

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->records[] = ['level' => $level, 'message' => (string)$message];
    }
}
