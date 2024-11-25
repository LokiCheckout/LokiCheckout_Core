<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Hyva\Theme\Service\CurrentTheme as HyvaCurrentTheme;
use Magento\Framework\ObjectManagerInterface;

class CurrentTheme
{
    public function __construct(
        private ObjectManagerInterface $objectManager
    ) {
    }

    public function isHyva(): bool
    {
        // @todo: Double-check that this compiles without Hyva as well
        $currentTheme = $this->objectManager->get(HyvaCurrentTheme::class);
        if (empty($currentTheme)) {
            return false;
        }

        return $currentTheme->isHyva();
    }
}
