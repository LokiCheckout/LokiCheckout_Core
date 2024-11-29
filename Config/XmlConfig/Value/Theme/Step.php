<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value\Theme;

use Magewirephp\Magewire\Component;

class Step
{
    public function __construct(
        private string $name,
        private Component $component
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getComponent(): Component
    {
        return $this->component;
    }
}
