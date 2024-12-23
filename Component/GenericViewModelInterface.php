<?php

namespace Yireo\LokiCheckout\Component;

use Yireo\LokiComponents\Component\ViewModelInterface;

interface GenericViewModelInterface extends ViewModelInterface
{
    public function isLazyLoad(): bool;
    public function isAllowRendering(): bool;
    public function getDomId(): string; // @todo: Remove
}
