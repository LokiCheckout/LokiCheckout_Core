<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic;

interface FormFieldInterface
{

    public function getFieldType(): string;
    public function getFieldName(): string;
    public function getTextType(): string;
    public function getValue(): string;
    public function setValue(mixed $value);
    public function isRequired(): bool;
    public function isValid(): bool;
}
