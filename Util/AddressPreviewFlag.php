<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

/**
 * Marks that an address preview is being rendered, so that address-specific template modifications
 * are not applied to other templates (like CMS content or emails)
 */
class AddressPreviewFlag
{
    private int $depth = 0;

    public function start(): void
    {
        $this->depth++;
    }

    public function stop(): void
    {
        $this->depth = max(0, $this->depth - 1);
    }

    public function isActive(): bool
    {
        return $this->depth > 0;
    }
}
