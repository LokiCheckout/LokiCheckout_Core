<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Checkout\Model\CompositeConfigProvider;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CheckoutConfig implements ArgumentInterface
{
    public function __construct(
        private CompositeConfigProvider $configProvider
    )
    {
    }

    public function getJsonConfig(): string
    {
        return json_encode($this->configProvider->getConfig());
    }
}
