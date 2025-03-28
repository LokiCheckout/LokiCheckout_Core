<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Payment\Redirect;

use InvalidArgumentException;

class RedirectResolverListing
{
    private array $redirectResolvers = [];

    /**
     * @param RedirectResolverInterface[] $redirectResolvers
     */
    public function __construct(
        array $redirectResolvers = []
    ) {
        foreach ($redirectResolvers as $redirectResolver) {
            if (false === $redirectResolver instanceof RedirectResolverInterface) {
                throw new InvalidArgumentException('Unknown redirect resolver argument');
            }

            $this->redirectResolvers[] = $redirectResolver;
        }
    }

    /**
     * @return RedirectResolverInterface[]
     */
    public function getRedirectResolvers(): array
    {
        return $this->redirectResolvers;
    }
}
