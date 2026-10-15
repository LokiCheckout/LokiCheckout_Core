<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use Throwable;

class AppendChildNameToBlockHtmlObserver implements ObserverInterface
{
    public function __construct(
        private LayoutInterface $layout
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof AbstractBlock) {
            return;
        }

        try {
            $parentBlock = $block->getParentBlock();
        } catch (Throwable $e) {
            return;
        }

        if (false === $parentBlock instanceof AbstractBlock) {
            return;
        }

        if (true !== $parentBlock->getGrid()) {
            return;
        }

        $blockAlias = $this->layout->getElementAlias($block->getNameInLayout());
        if (false === $blockAlias) {
            return;
        }

        $transport = $observer->getEvent()->getTransport();
        $html = (string) $transport->getHtml();

        $html = $this->appendBlockAliasToBlockHtml($html, $blockAlias);
        $transport->setHtml($html);
    }
    private function appendBlockAliasToBlockHtml(string $blockHtml, string $blockAlias): string
    {
        if (trim($blockHtml, " \f\n\r\t\v\x00") === '') {
            return $blockHtml;
        }

        if (false === str_contains($blockHtml, '<div')) {
            return $blockHtml;
        }

        return preg_replace_callback(
            '/<([a-zA-Z][a-zA-Z0-9:-]*)([^>]*)>/',
            static function (array $matches) use ($blockAlias): string {
                return sprintf(
                    '<%s%s data-child-name="%s">',
                    $matches[1],
                    $matches[2],
                    htmlspecialchars((string)$blockAlias, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5)
                );
            },
            $blockHtml,
            1
        );
    }

}
