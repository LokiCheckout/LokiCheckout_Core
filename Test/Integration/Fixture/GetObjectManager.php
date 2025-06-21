<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Fixture;

use Magento\Framework\App\ObjectManager;

trait GetObjectManager
{
    /**
     * @return ObjectManager
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function getObjectManager(): ObjectManager
    {
        return ObjectManager::getInstance();
    }
}
