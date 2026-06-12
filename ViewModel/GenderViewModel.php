<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use LokiCheckout\Core\ViewModel\OptionModel\GenderOptionModel;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class GenderViewModel implements ArgumentInterface
{
    public function __construct(
        private GenderOptionModel $genderOptionModel
    ) {
    }

    public function getLabelById(int $id): string
    {
        $options = $this->genderOptionModel->getOptionsArray();
        if (array_key_exists($id, $options)) {
            return (string)$options[$id];
        }

        return (string)__('Not specified');
    }
}
