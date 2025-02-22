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

        $quote->collectTotals();

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true);
        //$shippingAddress->collectShippingRates(); // @todo: Remove?

        try {
            $availableMethods = $this->getContext()->getShippingMethodManagement()->getList($quote->getId());
        } catch (StateException|NoSuchEntityException $e) {
            $this->getGlobalMessageRegistry()->addError($e->getMessage());

            return [];
        }

        $this->getContext()->getTotalsCollector()->collectAddressTotals($quote, $shippingAddress);// @todo: Remove?

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
            $shippingAddress->setCollectShippingRates(true);
            $shippingAddress->collectShippingRates();
            $shippingAddress->setShippingMethod('');
            $this->getContext()->getCartRepository()->save($quote);

            $this->currentShippingMethod = '';

            return;
        }

        $rate = $quote->getShippingAddress()->getShippingRateByCode($shippingMethodCode);

        $shippingAddress->setCollectShippingRates(true);
        $shippingAddress->collectShippingRates();
        $shippingAddress->setShippingMethod($shippingMethodCode);
        //$shippingAddress->removeAllShippingRates();// @todo: Remove?
        $shippingAddress->addShippingRate($rate);// @todo: Remove?

        $this->getContext()->getCartRepository()->save($quote);

        $this->currentShippingMethod = $quote->getShippingAddress()->getShippingMethod();
    }

    protected function sanitizeShippingMethod(string $shippingMethodCode): string
    {
        if (empty($shippingMethodCode)) {
            return $shippingMethodCode;
        }

        if (false === array_key_exists($shippingMethodCode, $this->getShippingMethods())) {
            $this->component->getLocalMessageRegistry()->addError('Method not allowed');
            $shippingMethodCode = '';
        }

        if (empty($shippingMethodCode) && $this->getContext()->getModuleConfig()->selectDefaultShippingMethod()) {
            $shippingMethodCode = (string)array_key_first($this->getShippingMethods());
        }

        return $shippingMethodCode;
    }
}
