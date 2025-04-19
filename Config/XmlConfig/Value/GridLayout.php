<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value;

use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout\Block;
use Yireo\LokiCheckout\Exception\BlockNotFoundException;

class GridLayout
{
    public function __construct(
        private string $name,
        private string $label,
        private string $countryId = '',
        private int $defaultSortOrder = 0,
        private int $defaultColSpan = 6,
        private int $defaultMdColSpan = 6,
        private array $blocks = []
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

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function getDefaultSortOrder(): int
    {
        return $this->defaultSortOrder;
    }

    /**
     * @return Block[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * @param string $blockAlias
     * @return Block
     * @throws BlockNotFoundException
     */
    public function getBlock(string $blockAlias): Block
    {
        foreach ($this->getBlocks() as $block) {
            if ($block->getAlias() === $blockAlias) {
                return $block;
            }
        }

        throw new BlockNotFoundException((string)__('Block with name "%1" not found in grid layout', $blockAlias));
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
