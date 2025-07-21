<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use LokiCheckout\Core\Config\XmlConfig;

class ThemeOptions implements OptionSourceInterface
{
    public function __construct(
        private readonly XmlConfig $xmlConfig,
    ) {
    }

    public function toOptionArray()
    {
        $options = [];
        foreach ($this->xmlConfig->getThemes() as $theme) {
            $options[] = [
                'value' => $theme->getName(),
                'label' => $theme->getLabel(),
            ];
        }

        return $options;
    }
}
