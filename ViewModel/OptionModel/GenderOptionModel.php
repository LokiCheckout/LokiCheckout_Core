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

        return $this->getOptionsArray();
    }

    public function getOptionsArray(): array
    {
        return [
            1 => __('Male'),
            2 => __('Female'),
            3 => __('Not Specified'),
        ];
    }
}
