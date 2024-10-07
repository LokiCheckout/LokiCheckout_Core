<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic;

interface ButtonInterface
{
    public function execute();
    public function allow(): bool;
}
