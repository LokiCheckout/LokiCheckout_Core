<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\Data\ShippingMethodInterface;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method ShippingMethodsContext getContext()
 */
class ShippingMethodsRepository extends ComponentRepository
{
    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        static $shippingMethods = null;
        if (is_array($shippingMethods)) {
            return $shippingMethods;
        }

        $shippingAddress = $this->getContext()->shippingAddressState->getShippingAddress();
        $quote = $shippingAddress->getQuote();
        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates();

        $shippingMethods = [];
        foreach ($this->getContext()->shippingMethodManagement->getList($quote->getId()) as $shippingMethod) {
            $shippingMethodCode = $shippingMethod->getCarrierCode().'_'.$shippingMethod->getMethodCode();
            $shippingMethods[$shippingMethodCode] = $shippingMethod;
        }

        return $shippingMethods;
    }

    protected function getData(): mixed
    {
        $shippingAddress = $this->getContext()->shippingAddressState->getShippingAddress();

        $currentShippingMethod = (string)$shippingAddress->getShippingMethod();
        $sanitizedShippingMethod = $this->sanitizeShippingMethod($currentShippingMethod);

        if ($sanitizedShippingMethod !== $currentShippingMethod) {
            $this->saveData($sanitizedShippingMethod);
        }

        return $sanitizedShippingMethod;
    }

    protected function saveData(mixed $data): void
    {
        $shippingMethod = (string)$data['value'];
        $shippingMethod = $this->sanitizeShippingMethod($shippingMethod);

        $shippingAddress = $this->getContext()->shippingAddressState->getShippingAddress();
        $quote = $shippingAddress->getQuote();

        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shippingMethod);

        $this->getContext()->cartRepository->save($quote);
    }

    protected function sanitizeShippingMethod(string $shippingMethodCode): string
    {
        if (false === array_key_exists($shippingMethodCode, $this->getShippingMethods())) {
            // @todo: Show a warning that something went wrong
            $shippingMethodCode = '';
        }

        if (empty($shippingMethodCode) && $this->getContext()->moduleConfig->selectDefaultShippingMethod()) {
            $shippingMethodCode = (string)array_key_first($this->getShippingMethods());
        }

        return $shippingMethodCode;
    }
}
