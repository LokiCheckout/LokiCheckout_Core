<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout;

class Field
{
    public function __construct(
        private string $name,
        private int $sortOrder,
        private string $gridClass
    ) {
    }

    public function getName(): string
    {
        return $this->name;
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
