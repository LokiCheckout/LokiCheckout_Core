<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour;

use Magento\Framework\App\ObjectManager;
use Yireo\LokiCheckout\Filter\FilterRegistry;

trait FilterBehaviour
{
    protected array $filters = [];

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
        return array_merge($this->filters, (array)$this->getParent()->getFilters());
    }
}
