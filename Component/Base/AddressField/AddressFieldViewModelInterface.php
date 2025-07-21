<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\AddressField;

use Loki\Field\Component\Base\Field\FieldViewModelInterface;

interface AddressFieldViewModelInterface extends FieldViewModelInterface
{
    public function getStep(): string;
}
