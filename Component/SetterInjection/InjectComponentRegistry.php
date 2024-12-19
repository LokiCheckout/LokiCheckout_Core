<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\SetterInjection;

use Yireo\LokiCheckout\Component\ComponentRegistry;

trait InjectComponentRegistry
{
    protected ?ComponentRegistry $componentRegistry = null;

    public function setComponentRegistry(ComponentRegistry $componentRegistry)
    {
        $this->componentRegistry = $componentRegistry;
    }
}
