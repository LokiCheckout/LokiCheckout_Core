<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Streetline;

use Yireo\LokiCheckout\Util\StreetLinesFactory;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class StreetLineContext implements ComponentContextInterface
{
    public function __construct(
        public readonly ShippingAddressState $shippingAddressState,
        public readonly BillingAddressState $billingAddressState,
        public readonly StreetLinesFactory $streetLinesFactory,
    ) {
    }
}
