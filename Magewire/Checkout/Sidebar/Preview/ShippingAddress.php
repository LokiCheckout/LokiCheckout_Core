<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magento\Customer\Model\Address\Config as AddressConfig;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class ShippingAddress extends Component
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    public function __construct(
        private readonly AddressConfig $addressConfig,
        private readonly ShippingAddressState $shippingAddressState,
        private StepNavigator $stepNavigator
    ) {
    }

    public function getHtml(): string
    {
        try {
            $this->stepNavigator->getStepByCode('shipping');
        } catch(UnknownStepException) {
            return '';
        }

        $address = $this->shippingAddressState->getShippingAddress();
        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();

        return $renderer->renderArray($address);
    }
}
