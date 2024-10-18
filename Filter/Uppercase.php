<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Filter;

class Uppercase implements FilterInterface
{
    public function filter(string $value): string
    {
        return strtoupper($value);
    }
}
