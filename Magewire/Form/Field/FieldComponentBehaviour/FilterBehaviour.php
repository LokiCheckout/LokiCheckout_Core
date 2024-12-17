<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour;

use Magento\Framework\App\ObjectManager;
use Yireo\LokiCheckout\Filter\FilterRegistry;

trait FilterBehaviour
{
    protected array $filters = [];

    protected function filterValue(mixed $value)
    {
        if (is_string($value)) {
            $value = $this->filter($value);
        }

        if (is_array($value)) {
            foreach ($value as $index => $subvalue) {
                if (is_string($subvalue)) {
                    $value[$index] = $this->filter($subvalue);
                }
            }
        }

        return $value;
    }

    protected function filter(string $value): string
    {
        $filterRegistry = ObjectManager::getInstance()->get(FilterRegistry::class);
        $selectedFilters = $filterRegistry->getSelectedFilters($this->getFilters());

        foreach ($selectedFilters as $filter) {
            $value = $filter->filter($value);
        }

        return $value;
    }

    public function getFilters(): array
    {
        return array_merge($this->filters, array_values((array)$this->getParent()->getFilters()));
    }
}
