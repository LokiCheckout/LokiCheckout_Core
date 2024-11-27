<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout;

class Block
{
    public function __construct(
        private string $alias,
        private int $sortOrder,
        private string $gridClass
    ) {
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function getGridClass(): string
    {
        return $this->gridClass;
    }
}
