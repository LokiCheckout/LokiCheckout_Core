<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

interface FormTextMultipleFieldInterface extends FormFieldInterface
{
    public function getCount(): int;

    public function setCounter(int $counter);
}
