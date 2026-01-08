<?php 
declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\CssVariables;

use Magento\Framework\ObjectManagerInterface;

class CssVariableFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager
    ) {
    }

    // @phpstan-ignore-next-line
    public function create(string $name, string $value): CssVariable
    {
        // @phpstan-ignore-next-line
        return $this->objectManager->create(CssVariable::class, [
            'name' => $name,
            'value' => $value,
        ]);
    }
}
