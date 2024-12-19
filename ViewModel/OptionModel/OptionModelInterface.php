<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Yireo\LokiComponents\Component\ComponentInterface;

interface OptionModelInterface
{
    public function getOptions(ComponentInterface $component): array;
}
