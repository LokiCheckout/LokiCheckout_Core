<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiComponents\Component\ComponentInterface;


class GenderOptionModel implements ArgumentInterface, OptionModelInterface
{
    // @todo: Is there really no code in Magento for this?
    public function getOptions(ComponentInterface $component): array
    {
        return [
            '' => __(''),
            'male' => __('Male'),
            'female' => __('Female'),
            'other' => __('Other'),
        ];
    }
}
