<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Stub;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ViewInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Simulates a block that (indirectly) depends on App\View while the layout is being generated
 */
class AppViewDependencyStub implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        ObjectManager::getInstance()->get(ViewInterface::class);
    }
}
