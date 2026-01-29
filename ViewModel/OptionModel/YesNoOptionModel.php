<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Loki\Components\Component\ComponentViewModelInterface;
use Loki\Field\Util\OptionModelInterface;

class YesNoOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        return [
            '1' => 'Yes',
            '0' => 'No',
        ];
    }
}
