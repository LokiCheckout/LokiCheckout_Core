<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic;

interface FormTextMultipleFieldInterface extends FormFieldInterface
{
    public function getTextCount(): int;

    public function setCounter(int $counter);
}
