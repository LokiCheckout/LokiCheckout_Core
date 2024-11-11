<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Button;

interface ButtonInterface
{
    public function getJsName(): string;
    public function getElementId(): string;
    public function getStep(): string;
    public function submit();
    public function allow(): bool;
    public function isVisible(): bool;
}
