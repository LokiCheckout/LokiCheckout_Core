<?php

namespace Yireo\LokiCheckout\Filter;

interface FilterInterface
{
    public function filter(string $value): string;
}
