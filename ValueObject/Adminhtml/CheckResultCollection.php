<?php declare(strict_types=1);

namespace LokiCheckout\Core\ValueObject\Adminhtml;

class CheckResultCollection
{
    public function __construct(
        private array $checkResults = []
    ) {
    }

    public function add(CheckResult $checkResult): void
    {
        $this->checkResults[] = $checkResult;
    }

    public function get(): array
    {
        return $this->checkResults;
    }
}
