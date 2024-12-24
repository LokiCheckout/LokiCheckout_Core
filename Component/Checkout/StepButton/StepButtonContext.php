<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton;

use Magento\Framework\Message\ManagerInterface as MessageManager;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class StepButtonContext implements ComponentContextInterface
{
    public function __construct(
        public readonly CheckoutState $checkoutState,
        public readonly BillingAddressState $billingAddressState,
        public readonly StepNavigator $stepNavigator,
        public readonly MessageManager $messageManager,
    ) {
    }
}
