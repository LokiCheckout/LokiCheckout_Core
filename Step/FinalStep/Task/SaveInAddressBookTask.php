<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep\Task;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Session;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;

class SaveInAddressBookTask implements TaskInterface
{
    public function __construct(
        private readonly Session $customerSession,
        private readonly AddressRepositoryInterface $addressRepository,
    ) {
    }

    public function beforePlaceOrder(Quote $quote): void
    {
        if (false === $this->customerSession->isLoggedIn()) {
            return;
        }

        $shippingAddress = $quote->getShippingAddress();
        $billingAddress = $quote->getBillingAddress();

        if ($shippingAddress->getSameAsBilling() &&
            $shippingAddress->getSaveInAddressBook() &&
            !$shippingAddress->getCustomerAddressId()
        ) {
            $shippingAddressData = $shippingAddress->exportCustomerAddress();
            $this->saveAddressesAsDefault($quote, $shippingAddressData, $billingAddress);
            $shippingAddressData->setCustomerId($quote->getCustomerId());
            $this->addressRepository->save($shippingAddressData);
            $quote->addCustomerAddress($shippingAddressData);
            $shippingAddress->setCustomerAddressData($shippingAddressData);
            $shippingAddress->setCustomerAddressId($shippingAddressData->getId());
            $billingAddress->setCustomerAddressId($shippingAddressData->getId());
        }
    }

    public function afterPlaceOrder(int $orderId): void
    {
    }

    private function saveAddressesAsDefault(
        Quote $quote,
        AddressInterface $shippingAddressData,
        Address $billingAddress
    ): void {
        $customer = $quote->getCustomer();
        $hasDefaultBilling = (bool)$customer->getDefaultBilling();
        $hasDefaultShipping = (bool)$customer->getDefaultShipping();
        if (!$hasDefaultShipping) {
            $shippingAddressData->setIsDefaultShipping(true);
            if (!$hasDefaultBilling && !$billingAddress->getSaveInAddressBook()) {
                $shippingAddressData->setIsDefaultBilling(true);
            }
        }
    }
}
