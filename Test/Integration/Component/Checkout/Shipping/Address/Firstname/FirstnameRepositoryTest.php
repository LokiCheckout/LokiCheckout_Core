<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\Address\Firstname;

use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutRepositoryTestCase;
use Yireo\LokiComponents\Component\ComponentRegistry;

#[
    AppArea('frontend'),
]
class FirstnameRepositoryTest extends LokiCheckoutRepositoryTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.firstname';

    public function testSaveToQuote(): void
    {
        $componentRegistry = ObjectManager::getInstance()->get(ComponentRegistry::class);
        $component = $componentRegistry->getComponentByName(self::BLOCK_NAME);
        $this->assertRepositorySave($component, 'John');
    }

    public function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['John', true, []],
        ];
    }
}
