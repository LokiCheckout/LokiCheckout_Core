<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Fixture;

use LokiCheckout\Core\Test\Integration\Stub\CartRepositoryStub;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\TestFramework\Fixture\DataFixtureInterface;

class CartRepositoryStubFixture implements DataFixtureInterface
{
    public function apply(array $data = []): null
    {
        return null;
        $objectManager = ObjectManager::getInstance();
        $objectManager->configure([
            'preferences' => [
                CartRepositoryInterface::class => CartRepositoryStub::class
            ]
        ]);

        return null;
    }
}
