<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

use Yireo\LokiCheckout\ViewModel\OptionModel\OptionModelInterface;

interface FormSelectFieldInterface extends FormFieldInterface
{
    public function getOptions(): array;
    public function setOptionModel(OptionModelInterface $optionModel): void;
}
