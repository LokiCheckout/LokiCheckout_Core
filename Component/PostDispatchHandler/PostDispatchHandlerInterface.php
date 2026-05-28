<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\PostDispatchHandler;

interface PostDispatchHandlerInterface
{
    public function execute(array $componentUpdates = []): void;
}
