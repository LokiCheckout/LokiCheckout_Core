<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

use Magewirephp\Magewire\Component;

class GenericComponent extends Component
{
    public bool $visible = true;

    private string $originalTemplate = '';

    public function booted(): void
    {
        parent::booted();

        $this->originalTemplate = $this->getParent()->getTemplate();
        if (false === $this->visible) {
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

    /**
     * @return bool
     * @deprecated Use $this->visible directly instead
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function updatedVisible(bool $value): bool
    {
        if (false === $value) {
            $this->switchTemplate('Yireo_LokiCheckout::utils/not-visible.phtml');
        } else {
            $this->switchTemplate($this->originalTemplate);
        }

        return $value;
    }
}
