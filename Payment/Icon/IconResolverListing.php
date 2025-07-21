<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Icon;

use InvalidArgumentException;

class IconResolverListing
{
    private array $iconResolvers = [];

    /**
     * @param IconResolverInterface[] $iconResolvers
     */
    public function __construct(
        array $iconResolvers = [],
    ) {
        foreach ($iconResolvers as $iconResolver) {
            if (false === $iconResolver instanceof IconResolverInterface) {
                throw new InvalidArgumentException('Unknown icon resolver argument');
            }

            $this->iconResolvers[] = $iconResolver;
        }
    }

    /**
     * @return IconResolverInterface[]
     */
    public function getIconResolvers(): array
    {
        return $this->iconResolvers;
    }
}
