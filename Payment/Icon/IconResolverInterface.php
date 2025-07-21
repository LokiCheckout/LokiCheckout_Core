<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Icon;

interface IconResolverInterface
{
    public function resolve(IconResolverContext $iconResolverContext): false|string;
}
