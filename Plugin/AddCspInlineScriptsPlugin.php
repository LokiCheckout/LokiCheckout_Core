<?php declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use Magento\Framework\View\Element\Template;
use Yireo\CspUtilities\Util\ReplaceInlineScripts;

class AddCspInlineScriptsPlugin
{
    private readonly ReplaceInlineScripts $replaceInlineScripts;

    public function __construct(
        ReplaceInlineScripts $replaceInlineScripts
    ) {
        $this->replaceInlineScripts = $replaceInlineScripts;
    }

    public function afterToHtml(Template $block, $html): string
    {
        if (false === strstr((string)$block->getNameInLayout(), 'loki')) {
            return (string)$html;
        }

        return $this->replaceInlineScripts->replace((string)$html);
    }
}
