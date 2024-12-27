<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Field;

use Yireo\LokiComponents\Component\Component;

/**
 * @method FieldRepository getRepository()
 * @method FieldViewModel getViewModel()
 */
class FieldComponent extends Component
{
    public function getValidators(array $validators = []): array
    {
        $viewModel = $this->getViewModel();
        $validators[] = $viewModel->getFieldName();

        if ($viewModel->isRequired()) {
            $validators[] = 'required';
        }

        if ($viewModel->hasMinLength() || $viewModel->hasMaxLength()) {
            $validators[] = 'length';
        }

        return parent::getValidators($validators);
    }
}
