<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Filter;

class Trim implements FilterInterface
{
    public function filter(string $value): string
    {
        return trim($value);
    }
}
