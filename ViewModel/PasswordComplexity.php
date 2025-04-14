<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class PasswordComplexity implements ArgumentInterface
{
    public function __construct(
        private int $minimumLength = 8,
    ) {
    }

    public function getMinimumLength(): int
    {
        return $this->minimumLength;
    }
}
