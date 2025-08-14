<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Config\XmlConfig;

class Defaults
{
    public function __construct(
        private readonly int $sortOrder = 99,
        private readonly int $colSpan = 12,
        private readonly int $mdColSpan = 6
    ) {
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
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
