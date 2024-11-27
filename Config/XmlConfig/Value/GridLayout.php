<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value;

use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout\Field;
use Yireo\LokiCheckout\Exception\XmlConfig\FieldNotFoundException;

class GridLayout
{
    public function __construct(
        private string $name,
        private int $defaultSortOrder,
        private string $defaultGridClass,
        private array $fields = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDefaultSortOrder(): int
    {
        return $this->defaultSortOrder;
    }

    public function getDefaultGridClass(): string
    {
        return $this->defaultGridClass;
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param string $fieldName
     * @return Field
     * @throws FieldNotFoundException
     */
    public function getField(string $fieldName): Field
    {
        foreach ($this->getFields() as $field) {
            if ($field->getName() === $fieldName) {
                return $field;
            }
        }

        throw new FieldNotFoundException((string)__('Field with name "%1" not found in grid layout', $fieldName));
    }
}
