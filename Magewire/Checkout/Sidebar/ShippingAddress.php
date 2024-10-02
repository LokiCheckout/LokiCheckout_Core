<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\Sidebar;

use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\MagewireCheckout\Magewire\AbstractComponent;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;
use Magento\Directory\Model\CountryFactory;

class ShippingAddress extends AbstractComponent
{
    protected $listeners = ['quoteSave' => 'refresh'];

    public function __construct(
        private readonly AddressConfig $addressConfig,
        private readonly ScopeConfigInterface $scopeConfig,
        CheckoutState $checkoutState
    ) {
        parent::__construct($checkoutState);
    }

    public function getHtml(): string
    {
        $address = $this->checkoutState->getQuote()->getShippingAddress();

        if (!$address->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $address->setCountryId($defaultCountryId);
        }

        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();
        return $renderer->renderArray($address);
    }
}
