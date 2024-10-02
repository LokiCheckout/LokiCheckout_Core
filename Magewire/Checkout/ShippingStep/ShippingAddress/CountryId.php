<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Eav\Model\Config;
use Yireo\MagewireCheckout\Magewire\Generic\EavFormFieldComponent;
use Yireo\MagewireCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;

class CountryId extends EavFormFieldComponent implements FormSelectFieldInterface
{
    public function __construct(
        private readonly DirectoryHelper $directoryHelper,
        Config $eavConfig,
        CheckoutState $checkoutState
    ) {
        parent::__construct($eavConfig, $checkoutState);
    }

    public function updated($value, string $name)
    {
        $this->checkoutState->getQuote()->getShippingAddress()->setData('region', '');
        $this->checkoutState->getQuote()->getShippingAddress()->setData('region_id', '');

        return parent::updated($value, $name);
    }
    public function getAttributeCode(): string
    {
        return 'country_id';
    }

    public function getFieldType(): string
    {
        return 'select';
    }

    public function getOptions(): array
    {
        $countries = [];
        foreach ($this->directoryHelper->getCountryCollection() as $country) {
            $countries[$country->getCountryId()] = $country->getName();
        }

        asort($countries);

        return $countries;
    }
}
