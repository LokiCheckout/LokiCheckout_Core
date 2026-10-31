<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step\FinalStep;

use LokiCheckout\Core\Payment\Redirect\RedirectResolverInterface;
use LokiCheckout\Core\Payment\Redirect\RedirectResolverListing;
use LokiCheckout\Core\Step\FinalStep\Redirect;
use LokiCheckout\Core\Step\FinalStep\RedirectContext;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Test\Fixture\PlaceOrder as PlaceOrderFixture;
use Magento\Checkout\Test\Fixture\SetBillingAddress as SetBillingAddressFixture;
use Magento\Checkout\Test\Fixture\SetDeliveryMethod as SetDeliveryMethodFixture;
use Magento\Checkout\Test\Fixture\SetGuestEmail as SetGuestEmailFixture;
use Magento\Checkout\Test\Fixture\SetPaymentMethod as SetPaymentMethodFixture;
use Magento\Checkout\Test\Fixture\SetShippingAddress as SetShippingAddressFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class RedirectTest extends TestCase
{
    protected function tearDown(): void
    {
        $checkoutSession = ObjectManager::getInstance()->get(CheckoutSession::class);
        $checkoutSession->setLastOrderId(null);
        $checkoutSession->setRedirectUrl(null);
    }

    public function testGetReturnsSuccessUrlWhenNoOrder(): void
    {
        $subject = ObjectManager::getInstance()->get(Redirect::class);

        $url = $subject->get();
        $this->assertStringContainsString('checkout/onepage/success', $url);
    }

    #[
        DataFixture(ProductFixture::class, as: 'product'),
        DataFixture(GuestCartFixture::class, as: 'cart'),
        DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
        DataFixture(SetBillingAddressFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetShippingAddressFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetGuestEmailFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetDeliveryMethodFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetPaymentMethodFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(PlaceOrderFixture::class, ['cart_id' => '$cart.id$'], as: 'order'),
    ]
    public function testGetReturnsSuccessUrlWithoutSessionRedirectUrl(): void
    {
        $this->setLastOrder('');

        $url = ObjectManager::getInstance()->get(Redirect::class)->get();
        $this->assertStringContainsString('checkout/onepage/success', $url);
    }

    #[
        DataFixture(ProductFixture::class, as: 'product'),
        DataFixture(GuestCartFixture::class, as: 'cart'),
        DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
        DataFixture(SetBillingAddressFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetShippingAddressFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetGuestEmailFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetDeliveryMethodFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetPaymentMethodFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(PlaceOrderFixture::class, ['cart_id' => '$cart.id$'], as: 'order'),
    ]
    public function testGetFallsBackToOrderPlaceRedirectUrlInSession(): void
    {
        $this->setLastOrder('https://example.com/pay');

        $url = ObjectManager::getInstance()->get(Redirect::class)->get();
        $this->assertSame('https://example.com/pay', $url);
    }

    #[
        DataFixture(ProductFixture::class, as: 'product'),
        DataFixture(GuestCartFixture::class, as: 'cart'),
        DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
        DataFixture(SetBillingAddressFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetShippingAddressFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetGuestEmailFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetDeliveryMethodFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(SetPaymentMethodFixture::class, ['cart_id' => '$cart.id$']),
        DataFixture(PlaceOrderFixture::class, ['cart_id' => '$cart.id$'], as: 'order'),
    ]
    public function testModuleResolversTakePrecedenceOverSessionRedirectUrl(): void
    {
        $this->setLastOrder('https://example.com/pay');

        $moduleResolver = new class implements RedirectResolverInterface {
            public function resolve(RedirectContext $redirectContext): false|string
            {
                return 'https://example.com/module';
            }
        };

        $subject = ObjectManager::getInstance()->create(Redirect::class, [
            'redirectResolverListing' => new RedirectResolverListing([$moduleResolver]),
        ]);

        $this->assertSame('https://example.com/module', $subject->get());
    }

    private function setLastOrder(string $redirectUrl): void
    {
        $order = DataFixtureStorageManager::getStorage()->get('order');
        $this->assertInstanceOf(OrderInterface::class, $order);

        $checkoutSession = ObjectManager::getInstance()->get(CheckoutSession::class);
        $checkoutSession->setLastOrderId($order->getEntityId());
        $checkoutSession->setRedirectUrl($redirectUrl);
    }
}
