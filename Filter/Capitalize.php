<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Filter;

class Capitalize implements FilterInterface
{
    public function filter(string $value): string
    {
        return ucfirst($value);
    }
}
