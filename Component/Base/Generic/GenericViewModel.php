<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method GenericContext getContext()
 */
class GenericViewModel extends ComponentViewModel implements GenericViewModelInterface
{
    protected string $step = '';

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


    public function setStep(string $step): void
    {
        $this->step = $step;
    }

    public function getStep(): string
    {
        if (!empty($this->step)) {
            return $this->step;
        }

        $this->step = $this->getContext()->getStepProvider()->getStep($this->getBlock());
        return $this->step;
    }

    public function getJsData(): ?array
    {
        return [
            'step' => $this->getStep(),
        ];
    }
}
