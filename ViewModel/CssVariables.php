<?php
declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use LokiCheckout\Core\ViewModel\CssVariables\CssVariable;
use LokiCheckout\Core\ViewModel\CssVariables\CssVariableFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CssVariables implements ArgumentInterface
{
    /** @var CssVariable[] $cssVariables */
    private array $cssVariables = [];

    public function __construct(
        private readonly CssVariableFactory $cssVariableFactory
    ) {
    }

    /**
     * @return CssVariable[]
     */
    public function get(): array
    {
        return $this->cssVariables;
    }

    public function set(string $name, string $value): void
    {
        $cssVariable = $this->cssVariableFactory->create($name, $value);
        $this->cssVariables[$cssVariable->getName()] = $cssVariable;
    }

    public function add(CssVariable $cssVariable): void
    {
        $this->cssVariables[$cssVariable->getName()] = $cssVariable;
    }
}
