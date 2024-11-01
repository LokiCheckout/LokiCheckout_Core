<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form;

interface ButtonInterface
{
    public function execute();
    public function allow(): bool;
}
