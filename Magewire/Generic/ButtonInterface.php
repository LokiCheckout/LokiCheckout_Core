<?php
declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Generic;

interface ButtonInterface
{
    public function execute();
    public function allow(): bool;
}
