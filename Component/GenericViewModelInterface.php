<?php

namespace Yireo\LokiCheckout\Component;

use Yireo\LokiComponents\Component\ViewModel\ViewModelInterface;

interface GenericViewModelInterface extends ViewModelInterface
{
    public function isLazyLoad(): bool;
    public function isAllowRendering(): bool;
    public function getDomId(): string; // @todo: Remove
    public function getTargets(): array; // @todo: Remove
}
