<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magewirephp\Magewire\Component;

class AccountTypeOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function getOptions(Component $component): array
    {
        return [
            'individual' => __('Individual'),
            'company' => __('Company'),
        ];
    }
}
