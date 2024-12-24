<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Yireo\LokiComponents\Component\ComponentViewModelInterface;

interface OptionModelInterface
{
    public function getOptions(ComponentViewModelInterface $viewModel): array;
}
