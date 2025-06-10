<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Select;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote\Address;
use Yireo\LokiCheckout\Component\Checkout\Address\AddressFormContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
 */
class SelectRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        if (false === $this->getContext()->getCustomerProvider()->isLoggedIn()) {
            return 0;
        }

        $customer = $this->getContext()->getCustomerRepository()->getById(
            $this->getContext()->getCustomerSession()->getCustomerId()
        );

        $address = $this->getContext()->getCurrentAddress($this->getComponent());
        $selectedAddressId = (int)$address->getCustomerAddressId();
        if ($selectedAddressId === 0) {
            if ($this->getContext()->isBillingAddressComponent($this->getComponent())) {
                $selectedAddressId = (int)$customer->getDefaultBilling();
            } else {
                $selectedAddressId = (int)$customer->getDefaultShipping();
            }
        }

        $sanitizedAddressId = $this->sanitizeSelectedAddressId($selectedAddressId);

        if ($sanitizedAddressId === 0) {
            if ($this->getContext()->isBillingAddressComponent($this->getComponent())) {
                $sanitizedAddressId = $this->getContext()->getCustomerProvider()->getDefaultBillingAddressId();
            } else {
                $sanitizedAddressId = $this->getContext()->getCustomerProvider()->getDefaultShippingAddressId();
            }
        }

        if ($sanitizedAddressId > 0) {
            $this->saveValue($sanitizedAddressId);
        }

        return $sanitizedAddressId;
    }

    public function saveValue(mixed $value): void
    {
        if (false === $this->getContext()->getCustomerProvider()->isLoggedIn()) {
            return;
        }

        $selectedAddressId = $this->sanitizeSelectedAddressId((int)$value);
        if (false === $selectedAddressId > 0) {
            return;
        }

        $customerAddress = $this->getContext()->getCustomerAddressRepository()->getById($selectedAddressId);
        $quote = $this->getContext()->getCheckoutState()->getQuote();

        /** @var Address $address */
        $address = $this->getContext()->getCurrentAddress($this->getComponent());
        $address->importCustomerAddressData($customerAddress);
        $address->setCustomerAddressId($customerAddress->getId());

        $this->getContext()->getCheckoutState()->saveQuote($quote);
    }

    protected function sanitizeSelectedAddressId(int $selectedAddressId): int
    {
        foreach ($this->getContext()->getCustomerProvider()->getAddresses() as $address) {
            if (false === $address instanceof AddressInterface) {
                continue;
            }

            if ((int)$address->getId() === $selectedAddressId) {
                return $selectedAddressId;
            }
        }

        return 0;
    }
}
