<?php
declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Generic;

interface FormTextMultipleFieldInterface extends FormFieldInterface
{
    public function getTextCount(): int;

    public function setCounter(int $counter);
}
