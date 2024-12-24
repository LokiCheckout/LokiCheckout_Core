<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;

class PrefixOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
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
