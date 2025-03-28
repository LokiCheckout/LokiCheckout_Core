<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Shipment\Icon;

class IconResolverListing
{
    public function __construct(
        private array $iconResolvers = []
    ) {
    }

    /**
     * @return IconResolverInterface[]
     */
    public function getIconResolvers(): array
    {
        return $this->iconResolvers;
    }
}
