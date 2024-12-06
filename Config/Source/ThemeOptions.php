<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Yireo\LokiCheckout\Config\XmlConfig;

class ThemeOptions implements OptionSourceInterface
{
    public function __construct(
        private XmlConfig $xmlConfig,
    ) {
    }

    public function toOptionArray()
    {
        $options = [];
        foreach($this->xmlConfig->getThemes() as $theme) {
            $options[] = [
                'value' => $theme->getName(),
                'label' => $theme->getLabel(),
            ];
        }

        return $options;
    }
}
