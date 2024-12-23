<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value\Theme;

use Yireo\LokiComponents\Component\ViewModelInterface;

class Step
{
    public function __construct(
        private string $name,
        private ViewModelInterface $component
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getComponent(): ViewModelInterface
    {
        return $this->component;
    }
}
