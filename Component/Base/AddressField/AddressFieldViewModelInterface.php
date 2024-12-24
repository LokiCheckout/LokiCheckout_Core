<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\AddressField;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModelInterface;

interface AddressFieldViewModelInterface extends FieldViewModelInterface
{
    public function getStep(): string;
}
