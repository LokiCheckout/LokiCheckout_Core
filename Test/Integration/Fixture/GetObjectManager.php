<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Fixture;

use Magento\Framework\App\ObjectManager;

trait GetObjectManager
{
    /**
     * @return ObjectManager
     * @SuppressWarnings StaticAccess
     */
    protected function getObjectManager(): ObjectManager
    {
        return ObjectManager::getInstance();
    }
}
