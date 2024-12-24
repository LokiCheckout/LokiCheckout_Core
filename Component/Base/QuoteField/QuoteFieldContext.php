<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\QuoteField;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\FieldTemplateLocator;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class QuoteFieldContext implements ComponentContextInterface
{
    public function __construct(
        public readonly FieldTemplateLocator $fieldTemplateLocator,
        public readonly AttributeProvider $attributeProvider,
        public readonly ScopeConfigInterface $scopeConfig,
        public readonly Config $moduleConfig,
        public readonly StepProvider $stepProvider,
        public readonly CheckoutSession $checkoutSession,
        public readonly CustomerSession $customerSession
    ) {
    }
}
