<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\AddressField;

use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class AddressFieldContext implements ComponentContextInterface
{
    public function __construct(
        public readonly ShippingAddressState $shippingAddressState,
        public readonly BillingAddressState $billingAddressState,
    ) {
    }
}
