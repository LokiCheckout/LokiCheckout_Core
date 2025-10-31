<?php declare(strict_types=1);

namespace LokiCheckout\Core\CustomerData\Checkout;

use Magento\Quote\Api\Data\AddressInterface;

class BillingAddressDataCollector
{
    public function getData(AddressInterface $billingAddress): array
    {
        return [
            'email'      => $billingAddress->getEmail(),
            'prefix'     => $billingAddress->getPrefix(),
            'firstname'  => $billingAddress->getFirstname(),
            'middlename' => $billingAddress->getMiddlename(),
            'lastname'   => $billingAddress->getLastname(),
            'suffix'     => $billingAddress->getSuffix(),
            'street'     => $billingAddress->getStreet(),
            'postcode'   => $billingAddress->getPostcode(),
            'region'     => $billingAddress->getRegion(),
            'region_id'  => $billingAddress->getRegionId(),
            'city'       => $billingAddress->getCity(),
            'country'    => $billingAddress->getCountry(),
            'tax_amount' => $billingAddress->getTaxAmount(),
            'vat_id'     => $billingAddress->getVatId(),
            'telephone'  => $billingAddress->getTelephone(),
            'fax'        => $billingAddress->getFax(),
            'company'    => $billingAddress->getCompany(),
        ];
    }
}
