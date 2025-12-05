<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Shipping\ShippingMethods;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Loki\Components\Component\ComponentRepository;

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
        if ($this->shippingMethods !== []) {
            return $this->shippingMethods;
        }

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        if ($quote->isVirtual() || 0 == $quote->getItemsCount()) {
            return [];
        }

        $shippingAddress = $quote->getShippingAddress();
        if (!$shippingAddress->getCountryId()) {
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
            $shippingMethodCode = $shippingMethod->getCarrierCode() . '_' . $shippingMethod->getMethodCode();
            $this->shippingMethods[$shippingMethodCode] = $shippingMethod;
        }

        return $this->shippingMethods;
    }

    public function getValue(): mixed
    {
        if (strlen($this->currentShippingMethod) > 0) {
            return $this->currentShippingMethod;
        }

        $shippingAddress = $this->getContext()->getShippingAddressState()->getAddress();

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

        if (strlen($shippingMethodCode) === 0) {
            $shippingAddress->removeAllShippingRates();
            $shippingAddress->setShippingMethod('');
            $this->currentShippingMethod = '';

            return;
        }

        // @todo: Move this to specific ShippingMethod service
        $shippingAddress->setShippingMethod($shippingMethodCode);
        $shippingAddress->setCollectShippingRates(true);
        $shippingAddress->collectShippingRates();
        //$this->getContext()->getCheckoutState()->saveQuote($quote);

        $quote->collectTotals();
        //$this->getContext()->getCheckoutState()->saveQuote($quote);

        $shippingAddress->setShippingMethod($shippingMethodCode);
        $this->currentShippingMethod = $quote->getShippingAddress()->getShippingMethod();
    }

    protected function sanitizeShippingMethod(string $shippingMethodCode): string
    {
        if (strlen($shippingMethodCode) === 0) {
            return $this->getDefaultShippingMethod();
        }

        $shippingMethods = $this->getShippingMethods();
        if ($shippingMethods === []) {
            $this->component->getLocalMessageRegistry()->addError('No shipping methods available');

            return '';
        }

        if (false === array_key_exists($shippingMethodCode, $shippingMethods)) {
            $this->component->getLocalMessageRegistry()->addError('Chosen shipping method is not available');

            return $this->getDefaultShippingMethod();
        }

        return $shippingMethodCode;
    }

    private function getDefaultShippingMethod(): string
    {
        if (false === $this->getContext()->getModuleConfig()->selectDefaultShippingMethod()) {
            return '';
        }

        $shippingMethods = $this->getShippingMethods();
        if ($shippingMethods === []) {
            return '';
        }

        return (string)array_key_first($shippingMethods);
    }
}
