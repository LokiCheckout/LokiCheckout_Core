<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Loki\Components\Component\ComponentViewModelInterface;
use Loki\Field\Util\OptionModelInterface;

class PrefixOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        $prefixOptions = $this->scopeConfig->getValue('customer/address/prefix_options', ScopeInterface::SCOPE_STORE);
        $prefixOptions = explode(';', (string)$prefixOptions);

        $options = [];
        foreach ($prefixOptions as $prefixOption) {
            $prefixOption = trim($prefixOption, " \f\n\r\t\v\x00");
            if ($prefixOption === '' || $prefixOption === '0') {
                continue;
            }

            $options[$prefixOption] = $prefixOption;
        }

        return $options;
    }
}
