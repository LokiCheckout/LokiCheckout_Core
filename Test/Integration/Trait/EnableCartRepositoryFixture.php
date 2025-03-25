<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Trait;

use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartRepositoryFixture;

/**
 * Usage: Add the trait to a test and call the method in setUp())
 */
trait EnableCartRepositoryFixture
{
    public function enableCartRepositoryFixture()
    {
        $objectManager = ObjectManager::getInstance();
        $objectManager->configure([
            'preferences' => [
                CartRepositoryInterface::class => CartRepositoryFixture::class
            ]
        ]);

        $this->assertInstanceOf(
            CartRepositoryFixture::class,
            ObjectManager::getInstance()->get(CartRepositoryInterface::class)
        );
    }
}
