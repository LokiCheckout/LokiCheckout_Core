<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magento\Customer\Model\Address\Config as AddressConfig;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingAddress extends Component
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    public function __construct(
        private readonly AddressConfig $addressConfig,
        private readonly BillingAddressState $billingAddressState,
    ) {
    }

    public function isSameAsShipping(): bool
    {
        return (bool)$this->billingAddressState->getBillingAddress()->getSameAsBilling();
    }

    public function getHtml(): string
    {
        $address = $this->billingAddressState->getBillingAddress();
        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();

        return $renderer->renderArray($address);
    }
}
