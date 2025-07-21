<?php declare(strict_types=1);

namespace LokiCheckout\Core\Config\XmlConfig\Value\Theme;

class Step
{
    public function __construct(
        private readonly string $name,
        private readonly string $blockName
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBlockName(): string
    {
        return $this->blockName;
    }

}
