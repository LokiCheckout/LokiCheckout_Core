<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\Adminhtml;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\ViewModel\Adminhtml\SystemCheck\SystemCheckInterface;

class SystemCheckCollection implements ArgumentInterface
{
    public function __construct(
        private readonly array $systemChecks = []
    ) {
    }

    /**
     * @return SystemCheckInterface[]
     */
    public function getSystemChecks(): array
    {
        return $this->systemChecks;
    }
}
