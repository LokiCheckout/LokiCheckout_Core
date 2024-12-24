<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ShippingMethodsContext implements ComponentContextInterface
{
    public function __construct(
        public readonly ShippingMethodManagementInterface $shippingMethodManagement,
        public readonly ShippingAddressState $shippingAddressState,
        public readonly StepProvider $stepProvider,
        public readonly CartRepositoryInterface $cartRepository,
        public readonly Config $moduleConfig
    ) {
    }
}
