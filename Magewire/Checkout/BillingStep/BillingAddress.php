<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep;

use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\ViewModel\CheckoutState;

class BillingAddress  extends Component
{
    protected $listeners = ['quoteSave' => 'refresh'];

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
