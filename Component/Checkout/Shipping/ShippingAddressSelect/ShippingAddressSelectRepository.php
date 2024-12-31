<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddressSelect;

use Magento\Customer\Api\Data\AddressInterface;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\ShippingAddressContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressSelectRepository extends ComponentRepository
{
    public function getAddresses(): array
    {
        return $this->getContext()->getCustomerProvider()->getAddresses();
    }

    protected function getData(): mixed
    {
        if (false === $this->getContext()->getCustomerProvider()->isLoggedIn()) {
            return 0;
        }

        $quote = $this->getContext()->getQuote();
        $customer = $this->getContext()->getCustomerRepository()->getById(
            $this->getContext()->getCustomerSession()->getCustomerId()
        );

        $selectedAddressId = (int)$quote->getShippingAddress()->getCustomerAddressId();
        if (empty($selectedAddressId)) {
            $selectedAddressId = (int)$customer->getDefaultShipping();
        }

        $sanitizedAddressId = $this->sanitizeSelectedAddressId($selectedAddressId);

        if (empty($sanitizedAddressId)) {
            $sanitizedAddressId = $this->getContext()->getCustomerProvider()->getDefaultShippingAddressId();
        }

        if ($sanitizedAddressId !== $selectedAddressId) {
            $this->saveData($sanitizedAddressId);
        }

        return $sanitizedAddressId;
    }

    protected function saveData(mixed $data): void
    {
        if (false === $this->getContext()->getCustomerProvider()->isLoggedIn()) {
            return;
        }

        $selectedAddressId = $this->sanitizeSelectedAddressId((int)$data);
        if (false === $selectedAddressId > 0) {
            return;
        }

        $customerShippingAddress = $this->getContext()->getCustomerAddressRepository()->getById($selectedAddressId);

        if (false === $customerShippingAddress instanceof AddressInterface) {
            return;
        }

        $quoteShippingAddress = $this->getContext()->getQuote()->getShippingAddress();
        $quoteShippingAddress->importCustomerAddressData($customerShippingAddress);
        $quoteShippingAddress->setCustomerAddressId($customerShippingAddress->getId());
        $quoteShippingAddress->save(); // @todo
    }

    protected function sanitizeSelectedAddressId(int $selectedAddressId): int
    {
        foreach ($this->getAddresses() as $address) {
            if (false === $address instanceof AddressInterface) {
                continue;
            }

            if ($address->getId() === $selectedAddressId) {
                return $selectedAddressId;
            }
        }

        return 0;
    }
}
