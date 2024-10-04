<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Step;

use Exception;

interface StepInterface
{
    public function getCode(): string;
    public function getLabel(): string;
    public function isEnabled(): bool;
    public function isAccessable(): bool;
    public function validate():  true|array;
}
