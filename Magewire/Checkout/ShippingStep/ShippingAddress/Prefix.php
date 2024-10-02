<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Eav\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Yireo\MagewireCheckout\Magewire\Generic\EavFormFieldComponent;
use Yireo\MagewireCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;

class Prefix extends EavFormFieldComponent implements FormTextSelectFieldInterface
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        Config $eavConfig,
        CheckoutState $checkoutState) {
        parent::__construct($eavConfig, $checkoutState);
    }

    public function getAttributeCode(): string
    {
        return 'prefix';
    }

    public function getOptions(): array
    {
        $prefixOptions = $this->scopeConfig->getValue('customer/address/prefix_options', ScopeInterface::SCOPE_STORE);
        $prefixOptions = explode(',', (string)$prefixOptions);
        if (empty($prefixOptions)) {
            return [];
        }

        $options = [];
        foreach ($prefixOptions as $prefixOption) {
            $prefixOption = trim($prefixOption);
            if (empty($prefixOption)) {
                continue;
            }

            $options[$prefixOption] = $prefixOption;
        }

        return $options;
    }
}
