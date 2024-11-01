<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Button;

interface ButtonInterface
{
    public function getJsName(): string;
    public function getElementId(): string;
    public function execute();
    public function allow(): bool;
}
