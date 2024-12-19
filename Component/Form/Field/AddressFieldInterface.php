<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

interface AddressFieldInterface extends FieldInterface
{
    public function getAttributeCode(): string;
    public function getAttributeLabel(): string;
    public function getStep(): string;
}
