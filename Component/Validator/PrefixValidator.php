<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\ViewModel\OptionModel\PrefixOptionModel;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\Validator\ValidatorInterface;

class PrefixValidator implements ValidatorInterface
{
    public function __construct(
        private PrefixOptionModel $prefixOptionModel,
    ) {
    }

    public function validate(ComponentInterface $component): bool
    {
        $prefix = trim($component->getValue());

        if (empty($prefix)) {
            return true;
        }

        $prefixOptions = $this->prefixOptionModel->getOptions($component->getViewModel());
        if (empty($prefixOptions)) {
            return true;
        }

        if (false === in_array($prefix, $prefixOptions)) {
            $component->getMessages()->addError((string)__('Invalid prefix'));

            return false;
        }

        return true;
    }
}
