<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\PostDispatchHandler;

use LokiCheckout\Core\Util\SameAddressService;

class SameAddressCopy implements PostDispatchHandlerInterface
{
    public function __construct(
        private readonly SameAddressService $sameAddressService
    )
    {
    }

    public function execute(array $componentUpdates = []): void
    {
        if ($this->sameAddressService->isSame()) {
            $this->sameAddressService->copyAddressToAddress();
        }
    }
}
