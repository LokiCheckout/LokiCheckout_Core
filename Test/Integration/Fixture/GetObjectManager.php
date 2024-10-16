<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Fixture;

use Magento\TestFramework\ObjectManager;

trait GetObjectManager
{
    protected function getObjectManager(): ObjectManager
    {
        return ObjectManager::getInstance();
    }
}
