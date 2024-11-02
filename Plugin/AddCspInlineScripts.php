<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Framework\View\Element\Template;
use Yireo\CspUtilities\Util\ReplaceInlineScripts;

class AddCspInlineScripts
{
    private ReplaceInlineScripts $replaceInlineScripts;

    public function __construct(
        ReplaceInlineScripts $replaceInlineScripts
    ) {
        $this->replaceInlineScripts = $replaceInlineScripts;
    }

    public function afterToHtml(Template $block, $html): string
    {
        if (false === strstr((string)$block->getNameInLayout(), 'loki_checkout.')) {
            return (string)$html;
        }

        return $this->replaceInlineScripts->replace((string)$html);
    }
}
