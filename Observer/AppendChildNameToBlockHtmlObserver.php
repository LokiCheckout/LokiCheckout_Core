<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;

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

        $transport = $observer->getEvent()->getTransport();
        $html = (string) $transport->getHtml();

        $blockAlias = $this->layout->getElementAlias($block->getNameInLayout());
        if (false === $blockAlias) {
            return;
        }

        $parentBlock = $block->getParentBlock();
        if (false === $parentBlock instanceof AbstractBlock) {
            return;
        }

        if (true !== $parentBlock->getGrid()) {
            return;
        }

        $html = $this->appendBlockAliasToBlockHtml($html, $blockAlias);
        $transport->setHtml($html);
    }
    private function appendBlockAliasToBlockHtml(string $blockHtml, string $blockAlias): string
    {
        if (trim($blockHtml) === '') {
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
                    $blockAlias
                );
            },
            $blockHtml,
            1
        );
    }

}
