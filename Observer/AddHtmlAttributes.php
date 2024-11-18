<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Util\CamelCaseConvertor;

class AddHtmlAttributes implements ObserverInterface
{
    public function __construct(
        private CamelCaseConvertor $camelCaseConvertor
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        if (false === preg_match('/^loki.checkout/', (string)$block->getNameInLayout()) > 0) {
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
        if (true === in_array($htmlTag, ['script', 'template'])) {
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
        if (empty($attributes)) {
            return '';
        }

        $htmlAttribute = '';
        foreach ($attributes as $attributeName => $attributeValue) {
            // @todo: Allow for more variables
            $attributeValue = str_replace('{component_name}', $this->getComponentName($block), $attributeValue);
            $htmlAttribute .= ' ' . $attributeName . '="' . $attributeValue . '"';
        }

        return trim($htmlAttribute);
    }

    private function getComponentName(AbstractBlock $block): string
    {
        return $this->camelCaseConvertor->toCamelCase($block->getJsId());
    }
}
