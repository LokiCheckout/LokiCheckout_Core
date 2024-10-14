<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic;

use Yireo\FriggaCheckout\ViewModel\OptionModel\OptionModelInterface;

interface FormSelectFieldInterface extends FormFieldInterface
{
    public function getOptions(): array;
    public function setOptionModel(OptionModelInterface $optionModel): void;
}
