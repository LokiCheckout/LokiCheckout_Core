<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\ShippingMethodManagementInterface;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ShippingMethodsContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly GenericContext $parentContext,
        private readonly ShippingMethodManagementInterface $shippingMethodManagement,
    ) {
    }

    public function getShippingMethodManagement(): ShippingMethodManagementInterface
    {
        return $this->shippingMethodManagement;
    }
}
