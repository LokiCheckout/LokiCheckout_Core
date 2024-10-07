<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic;

interface FormSelectFieldInterface extends FormFieldInterface
{
    public function getOptions(): array;
}
