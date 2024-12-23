<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Filter;

use Yireo\LokiComponents\Filter\FilterInterface;

class CountryId implements FilterInterface
{
    public function filter(string $value): string
    {
        $value = strtoupper($value);
        $value = preg_replace('/([^A-Z]+)/', '', $value);
        if (strlen($value) < 2) {
            return $value;
        }

        return substr($value, 0, 2);
    }
}
