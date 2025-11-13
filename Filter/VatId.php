<?php declare(strict_types=1);

namespace LokiCheckout\Core\Filter;

use Loki\Components\Component\ComponentInterface;
use Loki\Components\Filter\FilterInterface;

class VatId implements FilterInterface
{
    public function filter(mixed $value, ?ComponentInterface $component = null, ?string $scope = null): mixed
    {
        $value = strtoupper((string)$value);
        return preg_replace('/([^A-Z0-9]+)/', '', $value);
    }
}
