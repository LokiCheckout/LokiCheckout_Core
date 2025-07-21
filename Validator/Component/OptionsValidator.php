<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator\Component;

use Loki\Components\Component\ComponentInterface;
use Loki\Components\Validator\ValidatorInterface;
use Loki\Field\Component\Base\Field\FieldViewModel;
use Loki\Field\Util\OptionModelInterface;

class OptionsValidator implements ValidatorInterface
{
    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        $value = trim((string)$value);
        if ($value === '' || $value === '0') {
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
        if ($options === []) {
            return true;
        }

        if (array_key_exists($value, $options)) {
            return true;
        }

        return ['Not a valid option'];
    }
}
