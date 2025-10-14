<?php declare(strict_types=1);

namespace LokiCheckout\Core\CustomerData\Checkout;

use Magento\Quote\Api\Data\AddressInterface;

class ShippingAddressDataCollector
{
    public function getData(AddressInterface $shippingAddress): array
    {
        return [
            'email'      => $shippingAddress->getEmail(),
            'prefix'     => $shippingAddress->getPrefix(),
            'firstname'  => $shippingAddress->getFirstname(),
            'middlename' => $shippingAddress->getMiddlename(),
            'lastname'   => $shippingAddress->getLastname(),
            'suffix'     => $shippingAddress->getSuffix(),
            'street'     => $shippingAddress->getStreet(),
            'postcode'   => $shippingAddress->getPostcode(),
            'region'     => $shippingAddress->getRegion(),
            'city'       => $shippingAddress->getCity(),
            'country'    => $shippingAddress->getCountry(),
            'tax_amount' => $shippingAddress->getTaxAmount(),
            'vat_id'     => $shippingAddress->getVatId(),
            'telephone'  => $shippingAddress->getTelephone(),
            'fax'        => $shippingAddress->getFax(),
            'company'    => $shippingAddress->getCompany(),
        ];
    }
}
