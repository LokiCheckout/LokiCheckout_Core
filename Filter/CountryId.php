<?php declare(strict_types=1);

namespace LokiCheckout\Core\Filter;

use Loki\Components\Component\ComponentInterface;
use Loki\Components\Filter\FilterInterface;

class CountryId implements FilterInterface
{
    public function filter(mixed $value, ?ComponentInterface $component = null, ?string $scope = null): mixed
    {
        $value = strtoupper((string)$value);
        $value = preg_replace('/([^A-Z]+)/', '', $value);
        if (strlen($value) < 2) {
            return $value;
        }

        return substr($value, 0, 2);
    }
}
