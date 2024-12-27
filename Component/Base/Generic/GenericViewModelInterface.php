<?php

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Yireo\LokiComponents\Component\ComponentViewModelInterface;

interface GenericViewModelInterface extends ComponentViewModelInterface
{
    public function isLazyLoad(): bool;
    public function isAllowRendering(): bool;
    public function getStep(): string;
}
