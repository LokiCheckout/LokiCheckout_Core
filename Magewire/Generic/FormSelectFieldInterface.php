<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic;

use Yireo\FriggaCheckout\ViewModel\SelectOptionsInterface;

interface FormSelectFieldInterface extends FormFieldInterface
{
    public function getOptions(): array;
    public function setOptions(SelectOptionsInterface $options): void;
}
