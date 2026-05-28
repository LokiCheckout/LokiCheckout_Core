<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use Magento\Customer\Block\Address\Renderer\RendererInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Quote\Model\Quote\Address;

class AddressRenderer
{
    public function __construct(
        private readonly AddressConfig $addressConfig,
        private readonly AddressMapper $addressMapper,
    ) {
    }

    public function getHtml(Address $address): string
    {
        $address = $address->getDataModel(0, 0);
        $addressData = $this->addressMapper->toFlatArray($address);
        if ($this->isEmpty($addressData)) {
            return '';
        }

        /** @var RendererInterface $renderer */
        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();
        $html = $renderer->renderArray($addressData);
        $html = preg_replace("#<br([ /]+)>#", '<br>', $html);
        $html = preg_replace('#(?:\s*<br>\s*)+#i', '<br>', $html);
        $html = preg_replace('#<br>$#i', '', $html);

        return $html;
    }

    private function isEmpty(array $addressData): bool
    {
        $isEmpty = true;
        foreach ($addressData as $fieldName => $fieldValue) {
            if (is_string($fieldValue)) {
                $fieldValue = trim($fieldValue);
            }

            if (is_array($fieldValue)
                && count($fieldValue) === 1
                && in_array(trim((string)$fieldValue[0]), ['', '0'], true)) {
                continue;
            }

            if (in_array($fieldName, ['id', 'customer_id', 'country_id', 'vat_id', 'gender', 'suffix', 'prefix'])) {
                continue;
            }

            if (empty($fieldValue)) {
                continue;
            }

            $isEmpty = false;
        }

        return $isEmpty;
    }
}
