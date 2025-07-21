<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\Adminhtml\SystemCheck;

use LokiCheckout\Core\ValueObject\Adminhtml\CheckResult;
use LokiCheckout\Core\ValueObject\Adminhtml\CheckResultCollection;
use LokiCheckout\Core\ValueObject\Adminhtml\CheckResultCollectionFactory;

abstract class SystemCheck implements SystemCheckInterface
{
    private readonly CheckResultCollection $checkResultCollection;

    public function __construct(
        CheckResultCollectionFactory $checkResultCollectionFactory,
    ) {
        $this->checkResultCollection = $checkResultCollectionFactory->create();
    }

    public function getCheckResultCollection(): CheckResultCollection
    {
        $this->execute();
        return $this->checkResultCollection;
    }

    abstract protected function execute(): void;

    public function addCheckResult(
        string $label,
        mixed $value,
        bool $valid,
        string $recommendation
    ): void {
        $this->checkResultCollection->add(
            new CheckResult(
                $label,
                $value,
                $valid,
                $recommendation
            )
        );
    }
}
