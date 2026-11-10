<?php declare(strict_types=1);

namespace LokiCheckout\Core\Config;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;

class LayoutConfig
{
    public const BLOCK_NAME = 'loki-checkout.config';

    private array $values = [];

    public function loadFromLayout(LayoutInterface $layout): void
    {
        $block = $layout->getBlock(self::BLOCK_NAME);
        if (false === $block instanceof AbstractBlock) {
            return;
        }

        $this->values = (array)$block->getData('config');
    }

    public function has(string $path): bool
    {
        return array_key_exists($path, $this->values);
    }

    public function getValue(string $path): mixed
    {
        return $this->values[$path] ?? null;
    }
}
