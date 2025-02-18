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
        return new CheckResult(
            $this->getScopeConfigPath(),
            $this->scopeConfig->getValue($this->getScopeConfigPath()),
            $this->getRecommendation()
        );
    }

    abstract protected function getScopeConfigPath(): string;

    abstract protected function getRecommendation(): string;
}
