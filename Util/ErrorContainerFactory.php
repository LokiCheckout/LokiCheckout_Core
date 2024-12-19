<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\ObjectManagerInterface;

class ErrorContainerFactory
{
    public function __construct(
        private ObjectManagerInterface $objectManager
    ) {
    }

    public function create(): ErrorContainer
    {
        return $this->objectManager->create(ErrorContainer::class);
    }
}
