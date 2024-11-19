<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ThemeOptions implements OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            [
                'value' => 'default',
                'label' => __('Multi Step Checkout (default)')
            ],
            [
                'value' => 'onestep',
                'label' => __('One Step Checkout')
            ],
            /*[
                'value' => 'simplestep',
                'label' => __('Simple Step Checkout')
            ]*/
        ];
    }
}
