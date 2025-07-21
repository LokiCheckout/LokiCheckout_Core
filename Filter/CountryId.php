<?php declare(strict_types=1);

namespace LokiCheckout\Core\Filter;

use Loki\Components\Filter\FilterInterface;

class CountryId implements FilterInterface
{
    public function filter(mixed $data): mixed
    {
        $value = strtoupper((string)$data);
        $value = preg_replace('/([^A-Z]+)/', '', $value);
        if (strlen($value) < 2) {
            return $value;
        }

        return substr($value, 0, 2);
    }
}
