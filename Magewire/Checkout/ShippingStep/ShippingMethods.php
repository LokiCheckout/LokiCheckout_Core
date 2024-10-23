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
        $quote =  $shippingAddress->getQuote();

        $this->value = (string)$shippingAddress->getShippingMethod();

        if (empty($this->value)) {
            // @todo: Load a default shipping method
        }

        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates();

        $this->shippingMethods = $this->shippingMethodManagement->getList($quote->getId());
    }

    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        return $this->shippingMethods;
    }

    public function updatedValue(string $value)
    {
        $shippingAddress = $this->shippingAddressState->getShippingAddress();
        $quote =  $shippingAddress->getQuote();

        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($value);

        $quote->save();

        $this->emit('shippingStepSave');
        return $value;
    }
}
