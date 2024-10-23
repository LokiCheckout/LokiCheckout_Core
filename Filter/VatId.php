<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Filter;

class VatId implements FilterInterface
{
    public function filter(string $value): string
    {
        $value = strtoupper($value);
        return preg_replace('/([^A-Z0-9]+)/', '', $value);
    }
}
