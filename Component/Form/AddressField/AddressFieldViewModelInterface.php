<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\AddressField;

interface AddressFieldViewModelInterface extends FieldViewModelInterface
{
    public function getAttributeCode(): string;
    public function getAttributeLabel(): string;
    public function getStep(): string;
}
