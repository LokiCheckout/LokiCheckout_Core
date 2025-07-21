<?php declare(strict_types=1);

namespace LokiCheckout\Core\Config\XmlConfig\Value;

use RuntimeException;
use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout\Grid;

class GridLayout
{
    public function __construct(
        private readonly string $name,
        private readonly string $label,
        private readonly int $defaultSortOrder = 99,
        private readonly int $defaultColSpan = 6,
        private readonly int $defaultMdColSpan = 6,
        private readonly array $grids = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDefaultSortOrder(): int
    {
        return $this->defaultSortOrder;
    }

    /**
     * @return Grid[]
     */
    public function getGrids(): array
    {
        return $this->grids;
    }

    public function getGridByName(string $name): Grid
    {
        if (empty($name)) {
            throw new RuntimeException('Grid not found');
        }

        foreach ($this->grids as $grid) {
            if ($grid->getName() === $name) {
                return $grid;
            }
        }

        throw new RuntimeException('Grid not found');
    }

    public function getGridByCountryId(string $countryId): Grid
    {
        if (empty($countryId)) {
            throw new RuntimeException('Grid not found');
        }

        foreach ($this->grids as $grid) {
            if ($grid->getCountryId() === $countryId) {
                return $grid;
            }
        }

        throw new RuntimeException('Grid not found');
    }

    public function getDefaultColSpan(): int
    {
        return $this->defaultColSpan;
    }

    public function getDefaultMdColSpan(): int
    {
        return $this->defaultMdColSpan;
    }
}
