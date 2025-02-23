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
    private array $shippingMethods = [];

    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        if (!empty($this->shippingMethods)) {
            return $this->shippingMethods;
        }

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        if ($quote->isVirtual() || 0 == $quote->getItemsCount()) {
            return [];
        }

        $shippingAddress = $quote->getShippingAddress();
        if(!$shippingAddress->getCountryId()) {
            return [];
        }

        $quote->collectTotals();
        $shippingAddress->setCollectShippingRates(true);

        try {
            $availableMethods = $this->getContext()->getShippingMethodManagement()->getList($quote->getId());
        } catch (StateException|NoSuchEntityException $e) {
            $this->getGlobalMessageRegistry()->addError($e->getMessage());

            return [];
        }

        foreach ($availableMethods as $shippingMethod) {
            $shippingMethodCode = $shippingMethod->getCarrierCode().'_'.$shippingMethod->getMethodCode();
            $this->shippingMethods[$shippingMethodCode] = $shippingMethod;
        }

        return $this->shippingMethods;
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
        $shippingMethodCode = (string)$value;
        $shippingMethodCode = $this->sanitizeShippingMethod($shippingMethodCode);

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        if (empty($shippingMethodCode)) {
            $shippingAddress->removeAllShippingRates();
            $shippingAddress->setShippingMethod('');
            $this->getContext()->getCartRepository()->save($quote);

            $this->currentShippingMethod = '';

            return;
        }

        $rate = $quote->getShippingAddress()->getShippingRateByCode($shippingMethodCode);

        $shippingAddress->setCollectShippingRates(true);
        $shippingAddress->collectShippingRates();
        $shippingAddress->setShippingMethod($shippingMethodCode);
        $shippingAddress->addShippingRate($rate);

        $this->getContext()->getCartRepository()->save($quote);

        $this->currentShippingMethod = $quote->getShippingAddress()->getShippingMethod();
    }

    protected function sanitizeShippingMethod(string $shippingMethodCode): string
    {
        if (empty($shippingMethodCode)) {
            return $this->getDefaultShippingMethod();
        }

        $shippingMethods = $this->getShippingMethods();
        if (empty($shippingMethods)) {
            $this->component->getLocalMessageRegistry()->addError('No shipping methods available');
            return '';
        }

        if (false === array_key_exists($shippingMethodCode, $shippingMethods)) {
            $this->component->getLocalMessageRegistry()->addError('Chosen shipping method is not available');
            return $this->getDefaultShippingMethod();
        }

        if (!empty($shippingMethodCode)) {
            return $shippingMethodCode;
        }

        return $this->getDefaultShippingMethod();
    }

    private function getDefaultShippingMethod(): string
    {
        if (false === $this->getContext()->getModuleConfig()->selectDefaultShippingMethod()) {
            return '';
        }

        $shippingMethods = $this->getShippingMethods();
        if (empty($shippingMethods)) {
            return '';
        }

        return (string)array_key_first($shippingMethods);
    }
}
