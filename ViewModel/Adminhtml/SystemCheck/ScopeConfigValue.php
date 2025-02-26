<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResultCollectionFactory;

abstract class ScopeConfigValue extends SystemCheck
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        CheckResultCollectionFactory $checkResultCollectionFactory,
    ) {
        parent::__construct($checkResultCollectionFactory);
    }

    protected function execute(): void
    {
        $value = $this->scopeConfig->getValue($this->getScopeConfigPath());

        $this->addCheckResult(
            $this->getScopeConfigPath(),
            $value,
            $this->isValid($value),
            $this->getRecommendation()
        );
    }

    protected function isValid($value): bool
    {
        return true;
    }

    abstract protected function getScopeConfigPath(): string;

    abstract protected function getRecommendation(): string;
}
