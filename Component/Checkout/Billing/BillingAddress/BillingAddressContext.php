<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Magento\Quote\Api\CartRepositoryInterface;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\Util\BillingAsShippingService;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class BillingAddressContext implements ComponentContextInterface
{
    public function __construct(
        public readonly CartRepositoryInterface $cartRepository,
        public readonly BillingAddressState $billingAddressState,
        public readonly ShippingAddressState $shippingAddressState,
        public readonly BillingAsShippingService $billingAsShippingService,
        public readonly AddressRenderer $addressRenderer,
    ) {
    }
}
