<?php
declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Loki\Components\Component\ComponentViewModelInterface;
use Loki\Field\Util\OptionModelInterface;

class GenderOptionModel implements ArgumentInterface, OptionModelInterface
{
    // @todo: Is there really no code in Magento for this?
    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        return [
            'male' => __('Male'),
            'female' => __('Female'),
            'other' => __('Other'),
        ];
    }
}
