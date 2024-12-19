<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Component\Form\Field\Field;

interface FieldValidatorInterface
{
    public function validate(Field $component): bool;
}
