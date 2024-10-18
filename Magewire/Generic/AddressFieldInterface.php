<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

interface AddressFieldInterface extends FormFieldInterface
{
    public function getAttributeCode(): string;
    public function getAttributeLabel(): string;
}
