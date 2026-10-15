<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Html;

class PushHtmlAttributes
{
    public function execute(string $html, array $attributes): string
    {
        if (empty($html)) {
            return $html;
        }

        $html = ltrim($html, " \f\n\r\t\v\x00");
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
            $attributeName = (string)$attributeName;
            if (false === $this->isValidAttributeName($attributeName)) {
                continue;
            }

            if (is_array($attributeValue) || is_object($attributeValue)) {
                continue;
            }

            $attributeValue = htmlspecialchars((string)$attributeValue, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
            $htmlAttribute = $attributeName . '="' . $attributeValue . '"';

            if (str_contains($newFirstTagHtml, ' ' . $attributeName . '=')) {
                if (in_array($attributeName, $replaceAttributes)) {
                    $newFirstTagHtml = preg_replace_callback(
                        '#\s' . preg_quote($attributeName, '#') . '=(["\'])([^\'"]+)(["\'])#',
                        static fn () => ' ' . $htmlAttribute,
                        $newFirstTagHtml
                    );
                }

                continue;
            }

            $newFirstTagHtml = preg_replace_callback(
                '/^<([a-z]{2,})/',
                static fn (array $tagMatch) => '<' . $tagMatch[1] . ' ' . $htmlAttribute,
                $newFirstTagHtml
            );
        }

        $html = substr_replace($html, $newFirstTagHtml, 0, strlen($firstTagHtml));

        return $html;
    }

    private function isValidAttributeName(string $attributeName): bool
    {
        return (bool)preg_match('/^[a-zA-Z_:@][a-zA-Z0-9_:.@-]*$/', $attributeName);
    }
}
