<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\Data\ShippingMethodInterface;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method ShippingMethodsContext getContext()
 */
class ShippingMethodsRepository extends ComponentRepository
{
    private string $currentShippingMethod = '';

    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        static $shippingMethods = null;
        if (is_array($shippingMethods)) {
            return $shippingMethods;
        }

        $shippingAddress = $this->getContext()->getShippingAddressState()->getShippingAddress();
        $quote = $shippingAddress->getQuote();
        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates();

        $shippingMethods = [];
        foreach ($this->getContext()->getShippingMethodManagement()->getList($quote->getId()) as $shippingMethod) {
            $shippingMethodCode = $shippingMethod->getCarrierCode().'_'.$shippingMethod->getMethodCode();
            $shippingMethods[$shippingMethodCode] = $shippingMethod;
        }

        return $shippingMethods;
    }

    protected function getData(): mixed
    {
        if (!empty($this->currentShippingMethod)) {
            return $this->currentShippingMethod;
        }

        $shippingAddress = $this->getContext()->getShippingAddressState()->getShippingAddress();

        $currentShippingMethod = (string)$shippingAddress->getShippingMethod();
        $sanitizedShippingMethod = $this->sanitizeShippingMethod($currentShippingMethod);

        if ($sanitizedShippingMethod !== $currentShippingMethod) {
            $this->saveData($sanitizedShippingMethod);
        }

        return $sanitizedShippingMethod;
    }

    protected function saveData(mixed $data): void
    {
        $shippingMethod = (string)$data;
        $shippingMethod = $this->sanitizeShippingMethod($shippingMethod);

        $shippingAddress = $this->getContext()->getShippingAddressState()->getShippingAddress();
        $quote = $shippingAddress->getQuote();

        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shippingMethod);

        $quote->save();

        $this->currentShippingMethod = $shippingMethod;
    }

    protected function sanitizeShippingMethod(string $shippingMethodCode): string
    {
        if (false === array_key_exists($shippingMethodCode, $this->getShippingMethods())) {
            $this->component->getViewModel()->getMessageManager()->addLocalError('Method not allowed'); // @todo: Beautify
            $shippingMethodCode = '';
        }

        if (empty($shippingMethodCode) && $this->getContext()->getModuleConfig()->selectDefaultShippingMethod()) {
            $this->component->getViewModel()->getMessageManager()->addLocalNotice('Setting default method'); // @todo: Make optional
            $shippingMethodCode = (string)array_key_first($this->getShippingMethods());
        }

        return $shippingMethodCode;
    }
}
