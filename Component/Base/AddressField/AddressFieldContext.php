<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\AddressField;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\Util\Component\AddressStateProvider;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Util\Component\AlpineComponentData;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\Component\FieldTemplateLocator;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class AddressFieldContext implements ComponentContextInterface
{
    public function __construct(
        public readonly FieldTemplateLocator $fieldTemplateLocator,
        public readonly AlpineComponentData $alpineComponentData,
        public readonly StepProvider $stepProvider,
        public readonly AddressStateProvider $addressStateProvider,
        public readonly AttributeProvider $attributeProvider,
        public readonly ScopeConfigInterface $scopeConfig,
        public readonly Config $moduleConfig
    ) {
    }
}
