<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\Preview\ShippingDetails;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Util\AddressRenderer;
use LokiCheckout\Core\ViewModel\CheckoutState;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;

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
            return '';
        }

        $methodTitle = trim($this->getMethodLabel($carrier, $carrierMethod));

        if ($methodTitle !== '' && $methodTitle !== '0') {
            $parts[] = $methodTitle;
        }

        $carrierTitle = trim($this->scopeConfig->getValue(
            'carriers/' . $carrierCode . '/title',
        ));

        if ($carrierTitle !== '' && $carrierTitle !== '0') {
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

        throw new InvalidArgumentException('Carrier not found: ' . $carrierCode);
    }

    private function getMethodLabel(AbstractCarrierInterface $carrier, string $carrierMethod): string
    {
        if (false === $carrier instanceof DataObject) {
            return '';
        }

        $carrierMethods = $carrier->getAllowedMethods();
        foreach ($carrierMethods as $carrierCode => $carrierLabel) {
            if ($carrierMethod === $carrierCode) {
                return (string)$carrierLabel;
            }
        }

        return '';
    }
}
