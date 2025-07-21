<?php declare(strict_types=1);

namespace LokiCheckout\Core\Config\XmlConfig\Value\GridLayout;

use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout\Grid\Block;
use LokiCheckout\Core\Exception\BlockNotFoundException;

class Grid
{
    public function __construct(
        private readonly string $name,
        private readonly string $countryId,
        private readonly string $parent,
        private readonly array $blocks = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function getParent(): string
    {
        return $this->parent;
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
}
