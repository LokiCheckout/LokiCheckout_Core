<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Shipment\Icon;

interface IconResolverInterface
{
    public function resolve(IconResolverContext $iconResolverContext): false|string;
}
