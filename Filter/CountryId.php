<?php declare(strict_types=1);

namespace LokiCheckout\Core\Filter;

use Loki\Components\Filter\FilterInterface;
use Loki\Components\Filter\FilterScope;

class CountryId implements FilterInterface
{
    public function filter(mixed $value, FilterScope $scope): mixed
    {
        $value = strtoupper((string)$value);
        $value = preg_replace('/([^A-Z]+)/', '', $value);
        if (strlen($value) < 2) {
            return $value;
        }

        return substr($value, 0, 2);
    }
}
