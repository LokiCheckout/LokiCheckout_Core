<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\Preview\ShippingDetails;

use InvalidArgumentException;
use LokiCheckout\Core\Util\SameAddressService;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Escaper;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Util\AddressRenderer;
use LokiCheckout\Core\ViewModel\CheckoutState;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;

class ShippingDetailsViewModel extends CheckoutViewModel
{
    protected bool $skipValidation = true;

    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ShippingConfig $shippingConfig,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly AddressRenderer $addressRenderer,
        private readonly SameAddressService $sameAddressService,
        private readonly Escaper $escaper,
        private string $separator = ' - '
    ) {
    }

    public function getAddressLabel(): string
    {
        if ($this->sameAddressService->isSame()) {
            return (string)__('Billing and Shipping Address');
        }

        return (string)__('Shipping Address');
    }

    public function getAddressHtml(): string
    {
        if ($this->checkoutState->getQuote()->isVirtual()) {
            return '';
        }

        $address = $this->shippingAddressState->getAddress();

        return $this->addressRenderer->getHtml($address);
    }

    public function getMethod(): string
    {
        return (string)$this->checkoutState->getQuote()->getShippingAddress()?->getShippingMethod();
    }

    public function getMethodHtml(): string
    {
        return $this->escaper->escapeHtml($this->getMethodLabel());
    }

    public function getMethodLabel(): string
    {
        if ($this->checkoutState->getQuote()->isVirtual()) {
            return '';
        }

        $rate = $this->checkoutState->getQuote()->getShippingAddress()->getShippingRateByCode($this->getMethod());
        if ($rate instanceof Rate) {
            return $rate->getCarrierTitle() . $this->separator . $rate->getMethodTitle();
        }

        $shippingMethod = $this->getMethod();
        if (empty($shippingMethod)) {
            return '';
        }

        if (strpos($shippingMethod, '_') === false) {
            $carrierCode = $shippingMethod;
            $carrierMethod = '';
        } else {
            list($carrierCode, $carrierMethod) = explode('_', $shippingMethod, 2);
        }

        $parts = [];

        try {
            $carrier = $this->getCarrier($carrierCode);
        } catch (InvalidArgumentException $e) {
            return (string)__($carrierMethod) . $this->separator . __($carrierCode);
        }

        $methodTitle = trim($this->getCarrierMethodLabel($carrier, $carrierMethod), " \f\n\r\t\v\x00");

        if ($methodTitle !== '' && $methodTitle !== '0') {
            $parts[] = $methodTitle;
        }

        $carrierTitle = trim(
            (string)$this->scopeConfig->getValue(
                'carriers/' . $carrierCode . '/title',
            ), " \f\n\r\t\v\x00"
        );

        if ($carrierTitle !== '' && $carrierTitle !== '0') {
            $parts[] = $carrierTitle;
        }

        return implode($this->separator, $parts);
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

    private function getCarrierMethodLabel(AbstractCarrierInterface $carrier, string $carrierMethod): string
    {
        if (false === $carrier instanceof DataObject) {
            return (string)__($carrierMethod);
        }

        $carrierMethods = $carrier->getAllowedMethods();
        foreach ($carrierMethods as $carrierCode => $carrierLabel) {
            if ($carrierMethod === $carrierCode) {
                return (string)$carrierLabel;
            }
        }

        return (string)__($carrierMethod);
    }
}
