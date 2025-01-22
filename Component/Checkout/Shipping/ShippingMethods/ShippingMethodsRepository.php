<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
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

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates();

        $shippingMethods = [];
        try {
            $availableMethods = $this->getContext()->getShippingMethodManagement()->getList($quote->getId());
        } catch (StateException|NoSuchEntityException $e) {
            $this->getGlobalMessageRegistry()->addError($e->getMessage());
            return [];
        }

        foreach ($availableMethods as $shippingMethod) {
            $shippingMethodCode = $shippingMethod->getCarrierCode().'_'.$shippingMethod->getMethodCode();
            $shippingMethods[$shippingMethodCode] = $shippingMethod;
        }

        return $shippingMethods;
    }

    public function getValue(): mixed
    {
        if (!empty($this->currentShippingMethod)) {
            return $this->currentShippingMethod;
        }

        $shippingAddress = $this->getContext()->getShippingAddressState()->getShippingAddress();

        $currentShippingMethod = (string)$shippingAddress->getShippingMethod();
        $sanitizedShippingMethod = $this->sanitizeShippingMethod($currentShippingMethod);

        if ($sanitizedShippingMethod !== $currentShippingMethod) {
            $this->saveValue($sanitizedShippingMethod);
        }

        return $sanitizedShippingMethod;
    }

    public function saveValue(mixed $value): void
    {
        $shippingMethod = (string)$value;
        $shippingMethod = $this->sanitizeShippingMethod($shippingMethod);

        $shippingAddress = $this->getContext()->getShippingAddressState()->getShippingAddress();
        $quote = $this->getContext()->getCheckoutState()->getQuote();

        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shippingMethod);

        $this->getContext()->getCartRepository()->save($quote);

        $this->currentShippingMethod = $shippingMethod;
    }

    protected function sanitizeShippingMethod(string $shippingMethodCode): string
    {
        if (empty($shippingMethodCode)) {
            return $shippingMethodCode;
        }

        if (false === array_key_exists($shippingMethodCode, $this->getShippingMethods())) {
            $this->component->getLocalMessageRegistry()->addError(
                $this->component,
                'Method not allowed'
            ); // @todo: Beautify
            $shippingMethodCode = '';
        }

        if (empty($shippingMethodCode) && $this->getContext()->getModuleConfig()->selectDefaultShippingMethod()) {
            $this->component->getLocalMessageRegistry()->addNotice(
                $this->component,
                'Setting default method'
            ); // @todo: Make optional
            $shippingMethodCode = (string)array_key_first($this->getShippingMethods());
        }

        return $shippingMethodCode;
    }
}
