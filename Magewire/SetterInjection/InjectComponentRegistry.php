<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\SetterInjection;

use Yireo\LokiCheckout\Magewire\ComponentRegistry;

trait InjectComponentRegistry
{
    protected ?ComponentRegistry $componentRegistry = null;

    public function setComponentRegistry(ComponentRegistry $componentRegistry)
    {
        $this->componentRegistry = $componentRegistry;
    }
}
