<?php declare(strict_types=1);

namespace LokiCheckout\Core\CustomerData;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Quote\Api\Data\CartItemInterface;

class Checkout implements SectionSourceInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private CustomerRepositoryInterface $customerRepository,
    ) {
    }

    public function getSectionData()
    {
        return [
            ...$this->getQuoteSectionData(),
            'customer'         => $this->getCustomerSectionData(),
            'cart_items'            => $this->getCartItemsSectionData(),
            'shipping_address' => $this->getShippingAddressSectionData(),
            'billing_address' => $this->getBillingAddressSectionData(),
        ];
    }

    public function getCustomerSectionData(): array
    {
        $customerId = (int)$this->checkoutState->getCustomerSession()->getCustomerId();

        $data = [
            'id'       => $customerId,
            'group_id' => 0,
        ];

        if ($this->checkoutState->getCustomerSession()->isLoggedIn()) {
            $customer = $this->customerRepository->getById($customerId);
            $sectionData['customer']['group_id'] = $customer->getGroupId();
            // @todo: Make this extensible
        }

        return $data;
    }

    public function getQuoteSectionData(): array
    {
        return [
            'currency_code'  => $this->checkoutState->getQuote()->getQuoteCurrencyCode(),
            'grand_total'    => $this->checkoutState->getQuote()->getGrandTotal(),
            'sub_total'      => $this->checkoutState->getQuote()->getSubtotal(),
            'coupon_code'    => $this->checkoutState->getQuote()->getCouponCode(),
            'payment_method' => $this->checkoutState->getQuote()->getPayment()->getMethod(),
            'shipping_method' => $this->checkoutState->getQuote()->getShippingAddress()->getShippingMethod(),
        ];
    }

    public function getCartItemsSectionData(): array
    {
        $items = [];
        foreach ($this->checkoutState->getQuote()->getAllItems() as $item) {
            /** @var CartItemInterface $item */
            $items[] = [
                'item_id' => $item->getItemId(),
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'qty' => $item->getQty(),
                'sku' => $item->getSku(),
                'product_type' => $item->getProductType(),
            ];
        }

        return $items;
    }

    public function getShippingAddressSectionData(): array
    {
        $shippingAddress = $this->checkoutState->getQuote()->getShippingAddress();

        return [
            'email'           => $shippingAddress->getEmail(),
            'prefix'          => $shippingAddress->getPrefix(),
            'firstname'       => $shippingAddress->getFirstname(),
            'middlename'      => $shippingAddress->getMiddlename(),
            'lastname'        => $shippingAddress->getLastname(),
            'suffix'          => $shippingAddress->getSuffix(),
            'street'          => $shippingAddress->getStreet(),
            'postcode'        => $shippingAddress->getPostcode(),
            'region'          => $shippingAddress->getRegion(),
            'city'            => $shippingAddress->getCity(),
            'country'         => $shippingAddress->getCountry(),
            'tax_amount'      => $shippingAddress->getTaxAmount(),
            'vat_id'          => $shippingAddress->getVatId(),
            'telephone'       => $shippingAddress->getTelephone(),
            'fax'             => $shippingAddress->getFax(),
            'company'         => $shippingAddress->getCompany(),
        ];
    }

    public function getBillingAddressSectionData(): array
    {
        $billingAddress = $this->checkoutState->getQuote()->getBillingAddress();

        return [
            'email'           => $billingAddress->getEmail(),
            'prefix'          => $billingAddress->getPrefix(),
            'firstname'       => $billingAddress->getFirstname(),
            'middlename'      => $billingAddress->getMiddlename(),
            'lastname'        => $billingAddress->getLastname(),
            'suffix'          => $billingAddress->getSuffix(),
            'street'          => $billingAddress->getStreet(),
            'postcode'        => $billingAddress->getPostcode(),
            'region'          => $billingAddress->getRegion(),
            'city'            => $billingAddress->getCity(),
            'country'         => $billingAddress->getCountry(),
            'tax_amount'      => $billingAddress->getTaxAmount(),
            'vat_id'          => $billingAddress->getVatId(),
            'telephone'       => $billingAddress->getTelephone(),
            'fax'             => $billingAddress->getFax(),
            'company'         => $billingAddress->getCompany(),
        ];
    }
}
