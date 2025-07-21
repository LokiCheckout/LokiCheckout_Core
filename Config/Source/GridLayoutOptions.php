<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use LokiCheckout\Core\Config\XmlConfig;

class GridLayoutOptions implements OptionSourceInterface
{
    public function __construct(
        private readonly XmlConfig $xmlConfig,
    ) {
    }

    public function toOptionArray()
    {
        $options = [];
        foreach ($this->xmlConfig->getGridLayouts() as $gridLayout) {
            $options[] = [
                'value' => $gridLayout->getName(),
                'label' => $gridLayout->getLabel(),
            ];
        }

        return $options;
    }
}
