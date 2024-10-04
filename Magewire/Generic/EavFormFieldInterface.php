<?php
declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Generic;

interface EavFormFieldInterface extends FormFieldInterface
{
    public function getAttributeCode(): string;
    public function getAttributeLabel(): string;
}
