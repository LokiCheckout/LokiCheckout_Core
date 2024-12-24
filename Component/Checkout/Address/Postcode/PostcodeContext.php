<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Postcode;

use Magento\Directory\Helper\Data as DirectoryData;
use Yireo\LokiCheckout\Util\Component\AddressStateProvider;
use Yireo\LokiCheckout\Util\Component\AlpineComponentData;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\Component\FieldTemplateLocator;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class PostcodeContext implements ComponentContextInterface
{
    public function __construct(
        public readonly FieldTemplateLocator $fieldTemplateLocator,
        public readonly AlpineComponentData $alpineComponentData,
        public readonly StepProvider $stepProvider,
        public readonly AddressStateProvider $addressStateProvider,
        public readonly AttributeProvider $attributeProvider,
        public readonly DirectoryData $directoryData,
    ) {
    }
}
