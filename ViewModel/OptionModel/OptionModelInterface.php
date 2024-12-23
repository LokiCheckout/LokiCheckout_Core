<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Yireo\LokiComponents\Component\ViewModelInterface;

interface OptionModelInterface
{
    public function getOptions(ViewModelInterface $viewModel): array;
}
