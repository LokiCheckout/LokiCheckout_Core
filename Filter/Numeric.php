<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Filter;

class Numeric implements FilterInterface
{
    public function filter(string $value): string
    {
        return preg_replace( '/[^0-9 ]/i', '', $value);
    }
}
