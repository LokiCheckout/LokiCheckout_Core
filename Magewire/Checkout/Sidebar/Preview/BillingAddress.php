<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class BillingAddress extends Component
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    public function __construct(
        private readonly AddressConfig $addressConfig,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly CheckoutState $checkoutState
    ) {
    }

    public function getHtml(): string
    {
        $address = $this->checkoutState->getQuote()->getBillingAddress();

        if (!$address->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $address->setCountryId($defaultCountryId);
        }

        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();

        return $renderer->renderArray($address);
    }
}
