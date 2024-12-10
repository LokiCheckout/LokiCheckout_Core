<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

use Magewirephp\Magewire\Component;

abstract class GenericComponent extends Component
{
    public bool $visible = true;
    public bool $allowRendering = true;

    public function booted(): void
    {
        parent::booted();

        if (false === $this->allowRendering) {
            $this->switchTemplate('Yireo_LokiCheckout::utils/not-rendered.phtml');
        }
    }

    public function addRefreshListener(string $listener)
    {
        $this->listeners[$listener] = 'refresh';
    }

    public function getListeners(): array
    {
        $parentBlock = $this->getParent();
        if ($parentBlock && $parentBlock->getListeners()) {
            $this->listeners = array_merge($this->listeners, (array)$parentBlock->getListeners());
        }

        return $this->listeners;
    }

    public function updatedAllowRendering(bool $allowRendering): bool
    {
        if (false === $allowRendering) {
            $this->switchTemplate('Yireo_LokiCheckout::utils/not-rendered.phtml');
        }

        return $allowRendering;
    }
}
