<?php declare(strict_types=1);

namespace LokiCheckout\Core\Config\XmlConfig\Value\GridLayout\Grid;

class Block
{
    public function __construct(
        private readonly string $alias,
        private readonly int $sortOrder,
        private readonly int $colSpan,
        private readonly int $mdColSpan,
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

    public function getColSpan(): int
    {
        if (false === $this->colSpan > 0) {
            return 6;
        }

        return $this->colSpan;
    }

    public function getMdColSpan(): int
    {
        return $this->mdColSpan;
    }
}
