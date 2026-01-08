<?php
declare(strict_types=1);

/** @phpstan-ignore-file */

namespace LokiCheckout\Core\ViewModel\CssVariables;

use Magento\Framework\ObjectManagerInterface;

class CssVariableFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager
    ) {
    }

    public function create(string $name, string $value): CssVariable
    {
        return $this->objectManager->create(CssVariable::class, [
            'name' => $name,
            'value' => $value,
        ]);
    }
}
