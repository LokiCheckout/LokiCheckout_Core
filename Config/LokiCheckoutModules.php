<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config;

class LokiCheckoutModules
{
    public function __construct(
        private array $modules = []
    ) {
    }

    /**
     * @return string[]
     */
    public function getModules(): array
    {
        return $this->modules;
    }
}
