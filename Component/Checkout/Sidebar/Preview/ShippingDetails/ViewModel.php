<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Preview\ShippingDetails;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ViewModel as ParentViewModel;

class ViewModel  extends ParentViewModel
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ShippingConfig $shippingConfig,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly AddressRenderer $addressRenderer
    ) {
    }

    public function getAddressHtml(): string
    {
        if ($this->shippingAddressState->getQuote()->isVirtual()) {
            return '';
        }

        $address = $this->shippingAddressState->getShippingAddress();
        return $this->addressRenderer->getHtml($address);
    }

    public function getMethodHtml(): string
    {
        if ($this->checkoutState->getQuote()->isVirtual()) {
            return '';
        }

        $shippingMethod = $this->checkoutState->getQuote()->getShippingAddress()?->getShippingMethod();
        if (empty($shippingMethod)) {
            return '';
        }

        list($carrierCode, $carrierMethod) = explode('_', $shippingMethod);

        $carrierTitle = $this->scopeConfig->getValue(
            'carriers/'.$carrierCode.'/title',
        );

        $carrier = $this->getCarrier($carrierCode);
        $methodTitle = $this->getMethodLabel($carrier, $carrierMethod);

        return $methodTitle.' / '.$carrierTitle;
    }

    private function getCarrier(string $carrierCode): AbstractCarrierInterface
    {
        foreach ($this->shippingConfig->getActiveCarriers() as $activeCarrier) {
            if ($activeCarrier->getCarrierCode() === $carrierCode) {
                return $activeCarrier;
            }
        }

        throw new \InvalidArgumentException('Carrier not found: '.$carrierCode);
    }

    private function getMethodLabel(AbstractCarrierInterface $carrier, string $carrierMethod): string
    {
        $carrierMethods = $carrier->getAllowedMethods();
        foreach ($carrierMethods as $carrierCode => $carrierLabel) {
            if ($carrierMethod === $carrierCode) {
                return $carrierLabel;
            }
        }

        return '';
    }
}
