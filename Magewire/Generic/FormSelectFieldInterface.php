<?php
declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Generic;

interface FormSelectFieldInterface extends FormFieldInterface
{
    public function getOptions(): array;
}
