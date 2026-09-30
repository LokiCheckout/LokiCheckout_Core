<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Html;

class PushHtmlAttributes
{
    public function execute(string $html, array $attributes): string
    {
        if (empty($html)) {
            return $html;
        }

        $html = ltrim($html);
        if (!preg_match('/^<([a-z]{2,})/', $html, $match)) {
            return $html;
        }

        if (empty($match[1])) {
            return $html;
        }

        $htmlTag = $match[1];
        if ($htmlTag === 'script') {
            return $html;
        }

        if (!preg_match('/^<([^>]+)>/', $html, $match)) {
            return $html;
        }

        $firstTagHtml = $match[0];
        $newFirstTagHtml = $firstTagHtml;

        $replaceAttributes = ['id'];
        foreach ($attributes as $attributeName => $attributeValue) {
            if (str_contains($newFirstTagHtml, ' ' . $attributeName . '=')) {
                if (in_array($attributeName, $replaceAttributes)) {
                    $newFirstTagHtml = preg_replace(
                        '#\s' . $attributeName . '=(["\'])([^\'"]+)(["\'])#',
                        ' '.$attributeName . '="' . $attributeValue . '"',
                        $newFirstTagHtml
                    );
                }

                continue;
            }

            $htmlAttribute = $attributeName . '="' . $attributeValue . '"';
            $newFirstTagHtml = preg_replace('/^<([a-z]{2,})/', '<\1 ' . $htmlAttribute, $newFirstTagHtml);
        }

        $html = substr_replace($html, $newFirstTagHtml, 0, strlen($firstTagHtml));

        return $html;
    }
}
