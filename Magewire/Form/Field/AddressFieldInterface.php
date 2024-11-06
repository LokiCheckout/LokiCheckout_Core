<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

interface AddressFieldInterface extends FormFieldInterface
{
    public function getAttributeCode(): string;
    public function getAttributeLabel(): string;
    public function getStep(): string;
}
