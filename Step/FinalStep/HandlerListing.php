<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep;

use LokiCheckout\Core\Step\FinalStep\Handler\HandlerInterface;

class HandlerListing
{
    public function __construct(
        private array $handlers = []
    ) {
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
