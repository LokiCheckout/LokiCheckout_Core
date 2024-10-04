<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep;

use Magento\Shipping\Model\Config as ShippingConfig;
use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;

class ShippingMethods extends Component
{
    public array $shippingMethods = [];
    public string $value = '';

    public function __construct(
        private readonly ShippingConfig $shippingConfig,
        private CheckoutState $checkoutState) {
    }

    public function boot()
    {
        // @todo Set default value?
        $this->value = (string)$this->checkoutState->getQuote()->getShippingAddress()->getShippingMethod();

        foreach ($this->shippingConfig->getActiveCarriers() as $carrier) {
            $carrierMethods = $carrier->getAllowedMethods();
            foreach ($carrierMethods as $carrierCode => $carrierMethod) {
                $this->shippingMethods[$carrierCode] = $carrierMethod;
                #$this->shippingMethods[$carrier->getCarrierCode().'_'.$carrierCode] = $carrierMethod;
            }
        }
    }

    public function updatedValue(string $value)
    {
        $this->checkoutState->getQuote()->getShippingAddress()->setShippingMethod($value);
        $this->checkoutState->getQuote()->save();
        $this->emit('quoteSave');
        return $value;
    }
}
