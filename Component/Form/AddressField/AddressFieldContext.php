<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\AddressField;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Util\Component\AddressStateProvider;
use Yireo\LokiCheckout\Component\Form\Field\FieldContext;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Util\Component\AlpineComponentData;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;

class AddressFieldContext extends FieldContext
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
