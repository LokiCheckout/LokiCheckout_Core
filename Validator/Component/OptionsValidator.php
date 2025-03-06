<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;
use Yireo\LokiComponents\Validator\ValidatorInterface;
use Yireo\LokiFieldComponents\Component\Base\Field\FieldViewModel;
use Yireo\LokiFieldComponents\Util\OptionModelInterface;

class OptionsValidator implements ValidatorInterface
{
    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        $value = trim((string)$value);
        if (empty($value)) {
            return true;
        }

        $viewModel = $component->getViewModel();
        if (false === $viewModel instanceof FieldViewModel) {
            return true;
        }

        $optionModel = $viewModel->getOptionModel();
        if (false === $optionModel instanceof OptionModelInterface) {
            return true;
        }

        $options = $optionModel->getOptions($viewModel);
        if (empty($options)) {
            return true;
        }

        if (array_key_exists($value, $options)) {
            return true;
        }

        return ['Not a valid option'];
    }
}
