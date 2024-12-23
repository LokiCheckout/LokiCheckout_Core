<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Filter;

use Yireo\LokiComponents\Filter\FilterInterface;

class VatId implements FilterInterface
{
    public function filter(mixed $data): mixed
    {
        $value = strtoupper((string)$data);
        return preg_replace('/([^A-Z0-9]+)/', '', $value);
    }
}
