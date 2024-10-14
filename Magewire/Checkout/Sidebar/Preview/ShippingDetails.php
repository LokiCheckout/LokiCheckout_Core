<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\Sidebar\Preview;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\ViewModel\CheckoutState;

class ShippingDetails extends Component
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ShippingConfig $shippingConfig,
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function getHtml(): string
    {
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
