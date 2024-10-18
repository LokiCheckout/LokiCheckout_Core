<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Filter;

class Lowercase implements FilterInterface
{
    public function filter(string $value): string
    {
        return strtolower($value);
    }
}
