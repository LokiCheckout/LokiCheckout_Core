<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep\Handler;

interface HandlerInterface
{
    public function handle(array $params): array;
}
