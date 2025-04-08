<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Preview\ShippingDetails;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class ShippingDetailsViewModel extends CheckoutViewModel
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
        if ($this->checkoutState->getQuote()->isVirtual()) {
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

        $parts = [];

        try {
            $carrier = $this->getCarrier($carrierCode);
        } catch (InvalidArgumentException $e) {
            return ''; // @todo: A method like PostNL does not show?
        }

        $methodTitle = trim($this->getMethodLabel($carrier, $carrierMethod));

        if (!empty($methodTitle)) {
            $parts[] = $methodTitle;
        }

        $carrierTitle = trim($this->scopeConfig->getValue(
            'carriers/'.$carrierCode.'/title',
        ));

        if (!empty($carrierTitle)) {
            $parts[] = $carrierTitle;
        }

        return implode(' / ', $parts);
    }

    private function getCarrier(string $carrierCode): AbstractCarrierInterface
    {
        foreach ($this->shippingConfig->getAllCarriers() as $activeCarrier) {
            if ($activeCarrier->getCarrierCode() === $carrierCode) {
                return $activeCarrier;
            }
        }

        throw new InvalidArgumentException('Carrier not found: '.$carrierCode);
    }

    private function getMethodLabel(AbstractCarrierInterface $carrier, string $carrierMethod): string
    {
        $carrierMethods = $carrier->getAllowedMethods();
        foreach ($carrierMethods as $carrierCode => $carrierLabel) {
            if ($carrierMethod === $carrierCode) {
                return (string)$carrierLabel;
            }
        }

        return '';
    }
}
