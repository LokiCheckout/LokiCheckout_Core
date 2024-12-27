<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\StreetLine;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Util\Component\AddressStateProvider;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\FieldTemplateLocator;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\StreetLinesFactory;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class StreetLineContext implements ComponentContextInterface
{
    public function __construct(
        public readonly FieldTemplateLocator $fieldTemplateLocator,
        public readonly StepProvider $stepProvider,
        public readonly AddressStateProvider $addressStateProvider,
        public readonly AttributeProvider $attributeProvider,
        public readonly ShippingAddressState $shippingAddressState,
        public readonly BillingAddressState $billingAddressState,
        public readonly StreetLinesFactory $streetLinesFactory,
        public readonly ScopeConfigInterface $scopeConfig,
        public readonly Config $moduleConfig
    ) {
    }
}
