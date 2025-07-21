<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

class ValueSanitizer
{
    public function sanitize(string $value, string $name = ''): string
    {
        $value = trim($value);
        $value = html_entity_decode($value);
        $value = strip_tags($value);
        $value = htmlentities($value);
        return $value;
    }
}
