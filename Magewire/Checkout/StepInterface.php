<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout;

use Magento\Framework\Phrase;

interface StepInterface
{
    public function getCode(): string;
    public function getLabel(): Phrase;
    public function isEnabled(): bool;
    public function isAccessable(): bool;
    public function isFirst(): bool;
    public function isLast(): bool;
}
