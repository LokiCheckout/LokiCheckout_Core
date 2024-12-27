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
        private CamelCaseConvertor $camelCaseConvertor,
        private IdConvertor $idConvertor
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        if (false === preg_match('/^loki-checkout/', (string)$block->getNameInLayout()) > 0) {
            return;
        }

        $transport = $observer->getEvent()->getTransport();
        $html = $transport->getHtml();
        if (empty($html)) {
            return;
        }

        if (false === preg_match('/^<([a-z]{2,})/', $html, $match)) {
            return;
        }

        if (empty($match[1])) {
            return;
        }

        $htmlTag = $match[1];
        if (true === in_array($htmlTag, ['script'])) {
            return;
        }

        $htmlAttributes = $this->getHtmlAttributes($block);
        if (empty($htmlAttributes)) {
            return;
        }

        $html = preg_replace('/^<([a-z]{2,})/', '<\1 ' . $htmlAttributes, $html);

        $transport->setHtml($html);

    }

    private function getHtmlAttributes(AbstractBlock $block): string
    {
        $attributes = (array)$block->getData('html_attributes');
        $attributes['id'] = $this->getElementId($block);

        $htmlAttribute = '';
        foreach ($attributes as $attributeName => $attributeValue) {
            // @todo: Remove this
            $attributeValue = str_replace('{component_name}', $this->getComponentName($block), $attributeValue);
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
