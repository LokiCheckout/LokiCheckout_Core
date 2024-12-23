<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component;

use Yireo\LokiComponents\Component\ComponentViewModel;

class GenericViewModel extends ComponentViewModel implements GenericViewModelInterface
{
    protected bool $lazyLoad = true;
    protected bool $allowRendering = true;

    /**
     * @return bool
     */
    public function isLazyLoad(): bool
    {
        return $this->lazyLoad;
    }

    public function isAllowRendering(): bool
    {
        return $this->allowRendering;
    }
}
