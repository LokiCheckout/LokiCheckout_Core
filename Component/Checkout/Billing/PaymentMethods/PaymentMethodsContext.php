<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethods;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Yireo\LokiCheckout\Config\Config as ModuleConfig;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\Util\BillingAsShippingService;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class PaymentMethodsContext implements ComponentContextInterface
{
    public function __construct(
        public readonly CheckoutState $checkoutState,
        public readonly PaymentMethodManagementInterface $paymentMethodManagement,
        public readonly CartRepositoryInterface $cartRepository,
        public readonly ModuleConfig $moduleConfig,
        public readonly StepProvider $stepProvider
    ) {
    }
}
