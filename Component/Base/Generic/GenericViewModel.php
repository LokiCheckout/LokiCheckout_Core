<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method GenericContext getContext()
 */
class GenericViewModel extends ComponentViewModel implements GenericViewModelInterface
{
    protected bool $lazyLoad = true;
    protected bool $allowRendering = true;

    // @todo: Make sure this is called
    public function initialize(): void
    {
    }

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
