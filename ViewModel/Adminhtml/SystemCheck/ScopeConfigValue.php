<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResult;

abstract class ScopeConfigValue implements SystemCheckInterface
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function getResult(): CheckResult
    {
        $value = $this->scopeConfig->getValue($this->getScopeConfigPath());
        return new CheckResult(
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
