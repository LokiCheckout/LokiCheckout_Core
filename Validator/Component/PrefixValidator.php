<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\ViewModel\OptionModel\PrefixOptionModel;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Validator\ValidatorInterface;

class PrefixValidator implements ValidatorInterface
{
    public function __construct(
        private PrefixOptionModel $prefixOptionModel,
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        $prefix = trim($value);

        if (empty($prefix)) {
            return true;
        }

        $prefixOptions = $this->prefixOptionModel->getOptions($component->getViewModel());
        if (empty($prefixOptions)) {
            return true;
        }

        if (false === in_array($prefix, $prefixOptions)) {
            return [(string)__('Invalid prefix')];
        }

        return true;
    }
}
