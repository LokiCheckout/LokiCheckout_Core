<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment;

use Magento\Quote\Api\Data\AddressInterface;

interface ShippingAddressResolverInterface
{
    public function resolve(): AddressInterface|null;
}
