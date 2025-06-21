<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Trait;

use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteRepository;
use Yireo\LokiCheckout\Test\Integration\Stub\CartRepositoryStub;

/**
 * Usage: Add the trait to a test and call the method in setUp())
 */
trait EnableCartRepositoryStub
{
    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function enableCartRepositoryStub(): void
    {
        $objectManager = ObjectManager::getInstance();
        $objectManager->configure([
            'preferences' => [
                CartRepositoryInterface::class => CartRepositoryStub::class
            ]
        ]);

        $this->assertInstanceOf(
            CartRepositoryStub::class,
            ObjectManager::getInstance()->get(CartRepositoryInterface::class)
        );
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function disableCartRepositoryStub(): void
    {
        $objectManager = ObjectManager::getInstance();
        $objectManager->configure([
            'preferences' => [
                CartRepositoryInterface::class => QuoteRepository::class
            ]
        ]);

        $this->assertInstanceOf(
            QuoteRepository::class,
            ObjectManager::getInstance()->get(CartRepositoryInterface::class)
        );
    }
}
