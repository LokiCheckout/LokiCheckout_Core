<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep;

use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class ShippingMethods extends Component
{
    private array $shippingMethods = [];

    public string $value = '';

    public function __construct(
        private readonly ShippingMethodManagementInterface $shippingMethodManagement,
        private readonly ShippingAddressState $shippingAddressState,
    ) {
    }

    public function boot()
    {
        $shippingAddress = $this->shippingAddressState->getShippingAddress();
        $quote = $shippingAddress->getQuote();
        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates();

        foreach ($this->shippingMethodManagement->getList($quote->getId()) as $shippingMethod) {
            $shippingMethodCode = $shippingMethod->getCarrierCode().'_'.$shippingMethod->getMethodCode();
            $this->shippingMethods[$shippingMethodCode] = $shippingMethod;
        }

        $this->value = (string)$shippingAddress->getShippingMethod();
        if (false === array_key_exists($this->value, $this->shippingMethods)) {
            $this->value = '';
        }

        if (empty($this->value)) {
            // @todo: Make this optional
            $firstShippingMethod = (string)array_key_first($this->shippingMethods);
            $this->value = $firstShippingMethod;
            $this->shippingAddressState->getShippingAddress()->setShippingMethod($firstShippingMethod);
            $quote->save();
        }
    }

    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        return $this->shippingMethods;
    }

    public function setValue(string $value)
    {
        $shippingAddress = $this->shippingAddressState->getShippingAddress();
        $quote = $shippingAddress->getQuote();

        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($value);

        $quote->save();

        $this->emit('shippingStepSave');

        return $value;
    }

    public function getJsName(): string
    {
        $name = ucwords($this->getParent()->getNameInLayout(), '_.-');

        return preg_replace('#[_.-]+#', '', $name);
    }
}
