<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\View\DesignInterface;

class CurrentTheme
{
    public function __construct(
        private readonly DesignInterface $design,
    ) {
    }

    public function isHyva(): bool
    {
        return $this->isTheme('Hyva/default');
    }

    public function isLuma(): bool
    {
        return $this->isTheme('Magento/blank');
    }

    public function isBreeze(): bool
    {
        return $this->isTheme('Swissup/breeze-blank');
    }

    private function isTheme(string $themeName): bool
    {
        $inheritedThemes = $this->design->getDesignTheme()->getInheritedThemes();

        foreach ($inheritedThemes as $inheritedTheme) {
            if ($inheritedTheme->getCode() === $themeName) {
                return true;
            }
        }

        return false;
    }
}
