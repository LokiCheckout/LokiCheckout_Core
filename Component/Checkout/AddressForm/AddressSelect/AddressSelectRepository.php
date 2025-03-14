<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\AddressForm\AddressSelect;

use Magento\Customer\Api\Data\AddressInterface;
use Yireo\LokiCheckout\Component\Checkout\AddressForm\AddressFormContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
 */
class AddressSelectRepository extends ComponentRepository
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
        if (empty($selectedAddressId)) {
            if ($this->getContext()->isBillingAddressComponent($this->getComponent())) {
                $selectedAddressId = (int)$customer->getDefaultBilling();
            } else {
                $selectedAddressId = (int)$customer->getDefaultShipping();
            }
        }

        $sanitizedAddressId = $this->sanitizeSelectedAddressId($selectedAddressId);

        if (empty($sanitizedAddressId)) {
            if ($this->getContext()->isBillingAddressComponent($this->getComponent())) {
                $sanitizedAddressId = $this->getContext()->getCustomerProvider()->getDefaultBillingAddressId();
            } else {
                $sanitizedAddressId = $this->getContext()->getCustomerProvider()->getDefaultShippingAddressId();
            }
        }

        if ($sanitizedAddressId !== $selectedAddressId) {
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
        if (false === $customerAddress instanceof AddressInterface) {
            return;
        }

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $address = $this->getContext()->getCurrentAddress($this->getComponent());
        $address->importCustomerAddressData($customerAddress);
        $address->setCustomerAddressId($customerAddress->getId());

        $this->getContext()->getCartRepository()->save($quote);
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
