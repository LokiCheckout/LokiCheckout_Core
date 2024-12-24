<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddressSelect;

use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\ShippingAddressContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressSelectRepository extends ComponentRepository
{
    public function getAddresses(): array
    {
        return $this->getContext()->customerProvider->getAddresses();
    }

    protected function getData(): mixed
    {
        if (false === $this->getContext()->customerProvider->isLoggedIn()) {
            return 0;
        }

        $quote = $this->getContext()->checkoutSession->getQuote();
        $customer = $this->getContext()->customerRepository->getById(
            $this->getContext()->customerSession->getCustomerId()
        );

        $selectedAddressId = (int)$quote->getShippingAddress()->getCustomerAddressId();
        if (empty($selectedAddressId)) {
            $selectedAddressId = (int)$customer->getDefaultShipping();
        }

        $sanitizedAddressId = $this->sanitizeSelectedAddressId($selectedAddressId);

        if (empty($sanitizedAddressId)) {
            $sanitizedAddressId = $this->getContext()->customerProvider->getDefaultShippingAddressId();
        }

        if ($sanitizedAddressId !== $selectedAddressId) {
            $this->saveData($sanitizedAddressId);
        }

        return $sanitizedAddressId;
    }

    protected function saveData(mixed $data): void
    {
        if (false === $this->getContext()->customerProvider->isLoggedIn()) {
            return;
        }

        $selectedAddressId = $this->sanitizeSelectedAddressId((int)$data);

        $customerShippingAddress = $this->getContext()->customerAddressRepository->getById($selectedAddressId);
        $quoteShippingAddress = $this->getContext()->checkoutSession->getQuote()->getShippingAddress();
        $quoteShippingAddress->importCustomerAddressData($customerShippingAddress);
        $quoteShippingAddress->setCustomerAddressId($customerShippingAddress->getId());
        $quoteShippingAddress->save(); // @todo
    }

    protected function sanitizeSelectedAddressId(int $selectedAddressId): int
    {
        foreach ($this->getAddresses() as $address) {
            if ($address->getId() === $selectedAddressId) {
                return $selectedAddressId;
            }
        }

        return 0;
    }
}
