<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Component\Base\Field\FieldViewModelInterface;
use Yireo\LokiCheckout\ViewModel\OptionModel\OptionModelInterface;

class FieldTemplateLocator
{
    public function getFieldTemplateFromViewModel(FieldViewModelInterface $viewModel): string
    {
        $block = $viewModel->getBlock();
        if (false === $block instanceof AbstractBlock) {
            return $this->getFieldTemplateByType('text');
        }

        $fieldTemplate = $block->getFieldTemplate();
        if (false === empty($fieldTemplate)) {
            return $fieldTemplate;
        }

        $fieldType = $block->getFieldType();
        if ($fieldType === 'text_select') {
            $fieldType = $this->getFieldTemplateFromTextSelect($block);
        }

        if (array_key_exists($fieldType, $this->getFieldTemplateMapping())) {
            return $this->getFieldTemplateByType($fieldType);
        }

        return $this->getFieldTemplateByType('text');
    }

    public function getFieldTemplateByType(string $type): string
    {
        return $this->getFieldTemplateMapping()[$type];
    }

    public function getFieldTemplateMapping(): array
    {
        return [
            'text' => 'Yireo_LokiCheckout::form/field/text.phtml',
            'textarea' => 'Yireo_LokiCheckout::form/field/textarea.phtml',
            'radio' => 'Yireo_LokiCheckout::form/field/radio.phtml',
            'checkbox' => 'Yireo_LokiCheckout::form/field/checkbox.phtml',
            'select' => 'Yireo_LokiCheckout::form/field/select.phtml',
        ];
    }

    /**
     * @param AbstractBlock $block
     * @return string
     * @todo: Allow for multiple field template locators
     */
    private function getFieldTemplateFromTextSelect(AbstractBlock $block): string
    {
        $viewModel = $block->getViewModel();
        $optionModel = $block->getOptionModel();

        if (false === $optionModel instanceof OptionModelInterface) {
            return 'text';
        }

        if (count($optionModel->getOptions($viewModel)) === 0) {
            return 'text';
        }

        $block->setOptionModel($optionModel);
        return 'select';
    }
}
