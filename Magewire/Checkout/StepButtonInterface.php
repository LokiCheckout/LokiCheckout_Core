<?php
declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout;

interface StepButtonInterface
{
    public function getLabel(): string;
    public function proceedToNextAction();
    public function allowAction(): bool;
}
