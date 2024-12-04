<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

use Magewirephp\Magewire\Component;

class GenericComponent extends Component
{
    public bool $visible = true;

    public function mount(): void
    {
        parent::mount();

        if (false === $this->isVisible()) {
            $this->switchTemplate('Yireo_LokiCheckout::utils/not-visible.phtml');
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

    public function isVisible(): bool
    {
        return $this->visible;
    }
}
