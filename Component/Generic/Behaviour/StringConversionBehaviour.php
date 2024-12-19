<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

trait StringConversionBehaviour
{
    public function removeDashes(string $text): string
    {
        return preg_replace('#([^a-zA-Z0-9]{1})#', '-', $text);
    }

    public function toCamelCase(string $text): string
    {
        $text = ucwords($text, '_.-');

        return preg_replace('#[_.-]+#', '', $text);
    }
}
