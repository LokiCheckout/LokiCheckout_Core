<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Yireo\LokiCheckout\Config\XmlConfig;

class GridLayoutOptions implements OptionSourceInterface
{
    public function __construct(
        private XmlConfig $xmlConfig,
    ) {
    }

    public function toOptionArray()
    {
        $options = [];
        foreach($this->xmlConfig->getGridLayouts() as $gridLayout) {
            $options[] = [
                'value' => $gridLayout->getName(),
                'label' => $gridLayout->getLabel(),
            ];
        }

        return $options;
    }
}
