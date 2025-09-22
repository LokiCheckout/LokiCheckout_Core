<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step;

use Loki\Components\Component\ComponentViewModelInterface;

interface StepViewModelInterface extends ComponentViewModelInterface
{
    public function getCode(): string;
    public function getLabel(): string;
    public function isEnabled(): bool;
    public function isAccessable(): bool;
    public function validate():  bool|array;
}
