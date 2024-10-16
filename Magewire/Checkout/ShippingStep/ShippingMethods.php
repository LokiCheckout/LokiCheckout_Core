<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep;

use Magento\Shipping\Model\Config as ShippingConfig;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class ShippingMethods extends Component
{
    public array $shippingMethods = [];
    public string $value = '';

    public function __construct(
        private readonly ShippingConfig $shippingConfig,
        private readonly CheckoutState $checkoutState) {
    }

    public function boot()
    {
        $this->value = (string)$this->checkoutState->getQuote()->getShippingAddress()->getShippingMethod();

        foreach ($this->shippingConfig->getActiveCarriers() as $carrier) {
            $carrierMethods = $carrier->getAllowedMethods();
            foreach ($carrierMethods as $carrierCode => $carrierMethod) {
                $this->shippingMethods[$carrier->getCarrierCode().'_'.$carrierCode] = $carrierMethod;
            }
        }
    }

    public function updatedValue(string $value)
    {
        $this->checkoutState->getQuote()->collectTotals();

        $this->checkoutState->getQuote()->getShippingAddress()
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($value);

        $this->checkoutState->getQuote()->save();

        $this->emit('shippingStepSave');
        return $value;
    }
}
