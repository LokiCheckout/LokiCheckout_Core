<?php
declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Loki\Components\Component\ComponentViewModelInterface;
use Loki\Field\Util\OptionModelInterface;

class GenderOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        $options = $viewModel->getBlock()->getOptions();
        if (!empty($options)) {
            return (array)$options;
        }

        return [
            'male' => __('Male'),
            'female' => __('Female'),
            'other' => __('Other'),
        ];
    }
}
