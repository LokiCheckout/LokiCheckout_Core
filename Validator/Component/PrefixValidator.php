<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;
use Yireo\LokiCheckout\ViewModel\OptionModel\PrefixOptionModel;

class PrefixValidator implements FieldValidatorInterface
{
    public function __construct(
        private PrefixOptionModel $prefixOptionModel,
    ) {
    }

    public function validate(FieldComponent $component): bool
    {
        $prefix = trim($component->getValue());

        if (empty($prefix)) {
            return true;
        }

        $prefixOptions = $this->prefixOptionModel->getOptions($component);
        if (empty($prefixOptions)) {
            return true;
        }

        if (false === in_array($prefix, $prefixOptions)) {
            $component->error('value', 'Invalid prefix');

            return false;
        }

        return true;
    }
}
