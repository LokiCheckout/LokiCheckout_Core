<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Postcode;

use Magento\Directory\Helper\Data as DirectoryData;
use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldContext;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class PostcodeContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly AddressFieldContext $parentContext,
        private readonly DirectoryData $directoryData,
    ) {
    }

    public function getDirectoryData(): DirectoryData
    {
        return $this->directoryData;
    }
}
