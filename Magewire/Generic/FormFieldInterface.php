<?php
declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Generic;

use Magento\Framework\Phrase;

interface FormFieldInterface
{
    public function getAttributeCode(): string;
    public function getAttributeLabel(): Phrase|string;
    public function getFieldType(): string;
    public function getInputType(): string;
    public function getValue(): string;
    public function isRequired(): bool;
}
