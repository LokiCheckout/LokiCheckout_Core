<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Config;

// @todo: Add etc/adminhtml/system.xml, etc/config.xml and make this configurable
class Config
{
    public function enableSameAsBilling(): bool
    {
        return true;
    }

    public function isDebug(): bool
    {
        return true;
    }
}
