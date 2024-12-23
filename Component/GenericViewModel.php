<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component;

use Yireo\LokiComponents\Component\ViewModel;

abstract class GenericViewModel extends ViewModel implements GenericViewModelInterface
{
    protected bool $visible = true;
    protected bool $lazyLoad = true;
    protected bool $allowRendering = true;

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

    public function getDomId(): string
    {
        $domId = trim((string)$this->block->getDomId());
        if (!empty($domId)) {
            return $domId;
        }

        // @todo: Change this with the regex again
        return $this->block->getNameInLayout();
    }
}
