<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Shipment\Icon;

class IconResolverListing
{
    public function __construct(
        private readonly LayoutIconResolver $layoutIconResolver,
        private readonly array $iconResolvers = [],
    ) {
    }

    /**
     * @return IconResolverInterface[]
     */
    public function getIconResolvers(): array
    {
        return [
            $this->layoutIconResolver,
            ...$this->iconResolvers,
        ];
    }
}
