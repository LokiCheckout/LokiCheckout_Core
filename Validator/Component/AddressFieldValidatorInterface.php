<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

interface AddressFieldValidatorInterface
{
    public function validate(AddressFieldComponent $component): bool;
}
