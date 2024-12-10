<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Button;

interface ButtonInterface
{
    public function getDomElementId(): string;
    public function getStep(): string;
    public function submit();
}
