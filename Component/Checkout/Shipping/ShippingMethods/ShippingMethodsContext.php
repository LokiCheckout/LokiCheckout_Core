<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\ShippingMethodManagementInterface;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;

class ShippingMethodsContext extends GenericContext
{
    public function getShippingMethodManagement(): ShippingMethodManagementInterface
    {
        return $this->get(ShippingMethodManagementInterface::class);
    }
}
