<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResult;
use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResultCollection;
use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResultCollectionFactory;

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
    ): void
    {
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
