<?php declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use LokiCheckout\Core\Component\Checkout\Sidebar\Preview\ShippingDetails\ShippingDetailsViewModel;
use LokiCheckout\Core\Shipment\ShippingAddressResolverListing;
use Magento\Quote\Model\Quote\Address;

class ReplaceAddressLabelWhenLocationPlugin
{
    public function __construct(
        private readonly ShippingAddressResolverListing $shippingAddressResolverListing,
        private readonly string $addressLabel = 'Pickup Location',
    ) {
    }

    public function afterGetAddressLabel(ShippingDetailsViewModel $subject, ?string $addressLabel): ?string
    {
        foreach ($this->shippingAddressResolverListing->getResolvers() as $resolver) {
            $address = $resolver->resolve();
            if ($address instanceof Address) {
                return (string)__($this->addressLabel);
            }
        }

        return $addressLabel;
    }
}
