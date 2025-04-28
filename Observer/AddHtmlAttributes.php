<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Yireo\LokiComponents\Util\CamelCaseConvertor;
use Yireo\LokiComponents\Util\IdConvertor;

class AddHtmlAttributes implements ObserverInterface
{
    public function __construct(
        private readonly CamelCaseConvertor $camelCaseConvertor,
        private readonly IdConvertor $idConvertor
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

        if (!preg_match('/^<([a-z]{2,})/', $html, $match)) {
            return;
        }

        if (empty($match[1])) {
            return;
        }

        $htmlTag = $match[1];
        if ($htmlTag === 'script') {
            return;
        }

        $htmlAttributes = $this->getHtmlAttributes($block, $html);
        if ($htmlAttributes === '' || $htmlAttributes === '0') {
            return;
        }

        $html = preg_replace('/^<([a-z]{2,})/', '<\1 ' . $htmlAttributes, $html);

        $transport->setHtml($html);
    }

    private function getHtmlAttributes(AbstractBlock $block, string $currentHtml): string
    {
        $attributes = (array)$block->getData('html_attributes');
        $attributes['id'] = $this->getElementId($block);

        if (!preg_match('/^<([^>]+)>/', $currentHtml, $match)) {
            return $currentHtml;
        }

        $firstTagHtml = $match[0];

        $htmlAttribute = '';
        foreach ($attributes as $attributeName => $attributeValue) {
            if (str_contains($firstTagHtml, ' ' . $attributeName . '="')) {
                continue;
            }

            $htmlAttribute .= ' ' . $attributeName . '="' . $attributeValue . '"';
        }

        return trim($htmlAttribute);
    }

    private function getElementId(AbstractBlock $block): string
    {
        $elementId = $block->getUniqId();
        if (!empty($elementId)) {
            return $this->idConvertor->toElementId($elementId);
        }

        $nameInLayout = strtolower((string)$block->getNameInLayout());
        return $this->idConvertor->toElementId($nameInLayout);
    }

    private function getComponentName(AbstractBlock $block): string
    {
        // @todo: How much are we depending upon getJsId() here?
        return $this->camelCaseConvertor->toCamelCase($block->getJsId());
    }
}
