<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingMethods;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\AppIsolation;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods\ShippingMethodsRepository;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiComponents\Component\ComponentRepositoryInterface;
use Magento\TestFramework\Fixture\Config;

#[
    AppIsolation(true),
    DbIsolation(true),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1]),
    AppArea('frontend'),
]
class ShippingMethodsRepositoryTest extends LokiTestCase
{
    const COMPONENT_NAME = 'loki-checkout.shipping.methods';

    protected function setUp(): void
    {
        parent::setUp();
        $this->getCart();
        $this->saveShippingAddressValues();
    }

    #[
        Config('carriers/flatrate/active', 1),
    ]
    public function testGetShippingMethodsWithFlatRate()
    {
        /** @var ShippingMethodsRepository $repository */
        $repository = $this->getComponentRepository(self::COMPONENT_NAME);
        $shippingMethods = $repository->getShippingMethods();
        $shippingMethodCodes = $this->getShippingMethodCodes($shippingMethods);
        $this->assertContains('flatrate', $shippingMethodCodes, implode(' ', $shippingMethodCodes));
    }

    #[
        Config('carriers/flatrate/active', 1),
        Config('carriers/freeshipping/active', 1),
        Config('carriers/freeshipping/free_shipping_subtotal', 50),
    ]
    public function testGetShippingMethodsWithFreeShipping()
    {
        $this->saveShippingAddressValues();

        /** @var ShippingMethodsRepository $repository */
        $repository = $this->getComponentRepository(self::COMPONENT_NAME);
        $shippingMethods = $repository->getShippingMethods();
        $shippingMethodCodes = $this->getShippingMethodCodes($shippingMethods);

        $this->assertContains('freeshipping', $shippingMethodCodes, implode(' ', $shippingMethodCodes));
        $this->assertContains('flatrate', $shippingMethodCodes, implode(' ', $shippingMethodCodes));
    }

    public function testSaveValueWithNonExistingShippingMethod()
    {
        /** @var ShippingMethodsRepository $repository */
        $repository = $this->getComponentRepository(self::COMPONENT_NAME);
        $repository->saveValue('foobar_foobar');

        $quote = $this->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $this->assertSame('', $shippingAddress->getShippingMethod());
    }

    private function getQuote(): Quote
    {
        $checkoutState = ObjectManager::getInstance()->get(CheckoutState::class);
        return $checkoutState->getQuote();
    }

    private function getComponentRepository(string $blockId): ComponentRepositoryInterface
    {
        $componentRegistry = ObjectManager::getInstance()->get(ComponentRegistry::class);
        $component = $componentRegistry->getComponentByName($blockId);
        return $component->getRepository();
    }

    private function saveShippingAddressValues(array $values = []): void
    {
        $quote = $this->getQuote();

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setPostcode($values['postcode'] ?? '1111AA');
        $shippingAddress->setCity($values['city'] ?? 'Amsterdam');
        $shippingAddress->setCountryId($values['country_id'] ?? 'NL');

        $cartRepository = ObjectManager::getInstance()->get(CartRepositoryInterface::class);
        $cartRepository->save($quote);
    }

    /**
     * @params $shippingMethods ShippingMethodInterface[]
     */
    private function getShippingMethodCodes(array $shippingMethods = []): array
    {
        $shippingMethodCodes = [];
        foreach ($shippingMethods as $shippingMethod) {
            $shippingMethodCodes[] = $shippingMethod->getMethodCode();
        }

        return $shippingMethodCodes;
    }
}
