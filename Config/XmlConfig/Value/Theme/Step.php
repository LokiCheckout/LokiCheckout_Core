<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value\Theme;

use Yireo\LokiComponents\Component\ComponentInterface;

class Step
{
    public function __construct(
        private string $name,
        private ComponentInterface $component
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getComponent(): ComponentInterface
    {
        return $this->component;
    }
}
