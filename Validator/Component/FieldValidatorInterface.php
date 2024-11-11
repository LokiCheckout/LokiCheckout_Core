<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;

interface FieldValidatorInterface
{
    public function validate(FieldComponent $component): bool;
}
