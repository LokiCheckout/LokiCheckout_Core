<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use LokiCheckout\Core\Config\XmlConfig;

class SkinOptions implements OptionSourceInterface
{
    public function __construct(
        private readonly XmlConfig $xmlConfig,
    ) {
    }

    public function toOptionArray()
    {
        $options = [];
        foreach ($this->xmlConfig->getSkins() as $skin) {
            $options[] = [
                'value' => $skin->getName(),
                'label' => $skin->getLabel(),
            ];
        }

        return $options;
    }
}
