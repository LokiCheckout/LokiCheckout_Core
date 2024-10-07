<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Source\Options;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class PrefixOptions
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
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
