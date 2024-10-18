<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Magento\Framework\App\ObjectManager;
use Yireo\LokiCheckout\Filter\FilterRegistry;

trait FilterBehaviour
{
    protected array $filters = [];

    protected function filter(string $value): string
    {
        $filters = array_merge($this->filters, (array)$this->getParent()->getFilters());
        $filterRegistry = ObjectManager::getInstance()->get(FilterRegistry::class);
        $selectedFilters = $filterRegistry->getSelectedFilters($filters);

        foreach ($selectedFilters as $filter) {
            $value = $filter->filter($value);
        }

        return $value;
    }
}
