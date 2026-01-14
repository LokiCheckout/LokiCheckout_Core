<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Loki\Components\Util\Block\GetElementId;
use LokiCheckout\Core\Util\Html\PushHtmlAttributes;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;

class AddHtmlAttributes implements ObserverInterface
{
    public function __construct(
        private readonly GetElementId $getElementId,
        private readonly PushHtmlAttributes $pushHtmlAttributes
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        if (!preg_match('/^loki-checkout/', (string)$block->getNameInLayout()) > 0) {
            return;
        }

        $transport = $observer->getEvent()->getTransport();

        $html = $transport->getHtml();
        if (empty($html)) {
            return;
        }


        $htmlAttributes = $this->getHtmlAttributes($block);
        if (empty($htmlAttributes)) {
            return;
        }

        $html = $this->pushHtmlAttributes->execute((string)$html, $htmlAttributes);
        $transport->setHtml($html);
    }

    private function getHtmlAttributes(AbstractBlock $block): array
    {
        $attributes = (array)$block->getData('html_attributes');
        $attributes['id'] = $this->getElementId->execute($block);
        return $attributes;
    }
}
