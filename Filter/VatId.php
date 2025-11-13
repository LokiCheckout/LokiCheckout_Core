<?php declare(strict_types=1);

namespace LokiCheckout\Core\Filter;

use Loki\Components\Filter\FilterInterface;
use Loki\Components\Filter\FilterScope;

class VatId implements FilterInterface
{
    public function filter(mixed $value, FilterScope $scope): mixed
    {
        $value = strtoupper((string)$value);
        return preg_replace('/([^A-Z0-9]+)/', '', $value);
    }
}
