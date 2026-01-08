<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Select;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote\Address;
use LokiCheckout\Core\Component\Checkout\Address\AddressFormContext;
use Loki\Components\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
 */
class SelectRepository extends ComponentRepository
{
    public function getValue(): int
    {
        if (false === $this->getContext()->getCustomerProvider()->isLoggedIn()) {
            return 0;
        }

        if ($this->getContext()->isNewAddress($this->getComponent())) {
            return 0;
        }

        $selectedAddressId = $this->getSelectedAddressId();
        $sanitizedAddressId = $this->getSanitizedAddressId($selectedAddressId);

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

        if ($this->getContext()->isNewAddress($this->getComponent())) {
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

        $shippingAddress = $quote->getShippingAddress();

        if ($quote->isVirtual()) {
            $shippingAddress->importCustomerAddressData($customerAddress);
            $shippingAddress->setCustomerAddressId($customerAddress->getId());
        }

        if ($shippingAddress->getSameAsBilling() && $address->getId() !== $shippingAddress->getId()) {
            $shippingAddress->importCustomerAddressData($customerAddress);
            $shippingAddress->setCustomerAddressId($customerAddress->getId());
        }

        $address->save(); // @phpstan-ignore-line
        $shippingAddress->save(); // @phpstan-ignore-line
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

    private function getSelectedAddressId(): int
    {
        if ($this->getContext()->isNewAddress($this->getComponent())) {
            return 0;
        }

        $address = $this->getContext()->getCurrentAddress($this->getComponent());
        $selectedAddressId = (int)$address->getCustomerAddressId();
        if ($selectedAddressId > 0) {
            return $selectedAddressId;
        }

        $customer = $this->getContext()->getCustomerRepository()->getById(
            $this->getContext()->getCustomerSession()->getCustomerId()
        );

        if ($this->getContext()->isBillingAddressComponent($this->getComponent())) {
            return (int)$customer->getDefaultBilling();
        }

        return (int)$customer->getDefaultShipping();
    }

    private function getSanitizedAddressId(int $selectedAddressId): int
    {
        $sanitizedAddressId = $this->sanitizeSelectedAddressId($selectedAddressId);
        if ($sanitizedAddressId > 0) {
            return $sanitizedAddressId;
        }

        if ($this->getContext()->isBillingAddressComponent($this->getComponent())) {
            return (int)$this->getContext()->getCustomerProvider()->getDefaultBillingAddressId();
        }

        return (int)$this->getContext()->getCustomerProvider()->getDefaultShippingAddressId();
    }
}
