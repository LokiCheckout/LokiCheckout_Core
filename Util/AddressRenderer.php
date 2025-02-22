<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Customer\Block\Address\Renderer\RendererInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Quote\Api\Data\AddressInterface;

class AddressRenderer
{
    public function __construct(
        private readonly AddressConfig $addressConfig,
        private readonly AddressMapper $addressMapper,
    ) {
    }

    public function getHtml(AddressInterface $address): string
    {
        $address = $address->getDataModel(0, 0);
        $addressData = $this->addressMapper->toFlatArray($address);
        if ($this->isEmpty($addressData)) {
            return '';
        }

        /** @var RendererInterface $renderer */
        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();
        $html = $renderer->renderArray($addressData);
        $html = preg_replace('/^<br(\s+)\/>$/msi', '', $html);
        return $html;
    }

    private function isEmpty(array $addressData): bool
    {
        $isEmpty = true;
        foreach ($addressData as $fieldName => $fieldValue) {
            if (is_string($fieldValue)) {
                $fieldValue = trim($fieldValue);
            }

            if (is_array($fieldValue) && count($fieldValue) === 1 && empty(trim((string)$fieldValue[0]))) {
                continue;
            }

            if (in_array($fieldName, ['id', 'country_id', 'vat_id'])) {
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
