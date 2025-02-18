<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck\SystemCheckInterface;

class SystemCheckCollection implements ArgumentInterface
{
    public function __construct(
        private array $checks = []
    ) {
    }

    /**
     * @return SystemCheckInterface[]
     */
    public function getSystemChecks(): array
    {
        return $this->checks;
    }
}
