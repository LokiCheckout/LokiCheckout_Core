<?php

namespace Yireo\LokiCheckout\Component;

use Yireo\LokiComponents\Component\ComponentViewModelInterface;

interface GenericViewModelInterface extends ComponentViewModelInterface
{
    public function isLazyLoad(): bool;
    public function isAllowRendering(): bool;
}
