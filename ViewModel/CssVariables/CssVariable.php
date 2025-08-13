<?php
declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\CssVariables;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class CssVariable
{
    public function __construct(
        private string $name,
        private string $value,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
