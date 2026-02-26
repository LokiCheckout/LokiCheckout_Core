<?php declare(strict_types=1);

namespace LokiCheckout\Core\CustomerData\Checkout;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;

class BillingAddressDataCollector
{
    public function getData(Address $billingAddress): array
    {
        return [
            'email'      => $billingAddress->getEmail(),
            'prefix'     => $billingAddress->getPrefix(),
            'fullname'       => $this->getCustomerFullname($billingAddress),
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

    private function getCustomerFullname(Address $address): string
    {
        $parts = [];
        $parts[] = $address->getFirstname();

        if ($address->getMiddlename()) {
            $parts[] = $address->getMiddlename();
        }

        $parts[] = $address->getLastname();

        return implode(' ', $parts);
    }
}
