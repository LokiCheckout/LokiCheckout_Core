<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\AddressField;

use Yireo\LokiCheckout\Component\Form\Field\FieldViewModelInterface;

interface AddressFieldViewModelInterface extends FieldViewModelInterface
{
    public function getStep(): string;
}
