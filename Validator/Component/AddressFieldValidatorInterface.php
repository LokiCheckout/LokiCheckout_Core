<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

interface AddressFieldValidatorInterface
{
    public function validate(AddressFieldComponent $component): bool;
}
