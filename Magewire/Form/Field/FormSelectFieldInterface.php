<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Yireo\LokiCheckout\ViewModel\OptionModel\OptionModelInterface;

interface FormSelectFieldInterface extends FormFieldInterface
{
    public function getOptions(): array;
    public function setOptionModel(OptionModelInterface $optionModel): void;
}
