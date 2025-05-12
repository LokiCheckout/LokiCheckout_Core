<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout;

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

    public function getGridClass(): string
    {
        if (false === $this->colSpan > 0) {
            $this->colSpan = 6;
        }

        $gridClass = 'col-span-' . $this->colSpan;
        if ($this->mdColSpan > 0) {
            $gridClass .= ' md:col-span-' . $this->mdColSpan;
        }

        return $gridClass;
    }

    public function getColSpan(): int
    {
        return $this->colSpan;
    }

    public function getMdColSpan(): int
    {
        return $this->mdColSpan;
    }

}
