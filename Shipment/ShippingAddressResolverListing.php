<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment;

use Magento\Quote\Model\Quote\Address;

class ShippingAddressResolverListing
{
    public function __construct(
        private readonly array $shippingAddressResolvers = [],
    ) {
    }

    /**
     * @return ShippingAddressResolverInterface[]
     */
    public function getResolvers(): array
    {
        return $this->shippingAddressResolvers;
    }

    public function resolves(): bool
    {
        $resolvers = $this->getResolvers();
        foreach ($resolvers as $resolver) {
            $address = $resolver->resolve();
            if ($address instanceof Address) {
                return true;
            }
        }

        return false;
    }
}
