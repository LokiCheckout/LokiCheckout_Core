<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Billing\PaymentMethods;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Test\Integration\Trait\RateLimiterStubs;
use LokiCheckout\Core\Test\Integration\Trait\SaveShippingAddressValues;
use Loki\Components\Component\ComponentInterface;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Payment;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[DbIsolation(true)]
#[DataFixture(ProductFixture::class, as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
class PaymentMethodsRepositoryRateLimitTest extends LokiCheckoutTestCase
{
    use RateLimiterStubs;
    use SaveShippingAddressValues;

    protected function setUp(): void
    {
        parent::setUp();
        $this->installRateLimiterStubs();
    }

    protected function tearDown(): void
    {
        $this->removeRateLimiterStubs();
        parent::tearDown();
    }

    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    #[ConfigFixture('payment/banktransfer/active', 1, 'store', 'default')]
    public function testSelectingAPaymentMethodOnlyUsesThePaymentSavingRateLimiter(): void
    {
        $component = $this->getMethodsComponent();

        $component->getRepository()->saveValue('checkmo');
        $component->getRepository()->saveValue('banktransfer');

        $this->assertSame('banktransfer', $this->getPersistedPayment()->getMethod());
        $this->assertSame(0, $this->processingRateLimiter->calls);
        $this->assertSame(2, $this->savingRateLimiter->calls);
    }

    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    public function testExceededPaymentSavingRateLimitDoesNotSaveTheMethod(): void
    {
        $component = $this->getMethodsComponent();
        $this->savingRateLimiter->exceeded = true;

        $component->getRepository()->saveValue('checkmo');

        $this->assertNotSame('checkmo', $this->getPersistedPayment()->getMethod());
        $errors = array_map(
            fn ($message) => $message->getText(),
            $this->getObjectManager()->get(MessageManager::class)->getMessages()->getErrors()
        );
        $this->assertContains('Rate limit exceeded', $errors);
    }

    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    #[ConfigFixture('payment/banktransfer/active', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/billing/select_default_payment_method', 1, 'store', 'default')]
    public function testRenderingTheCheckoutDoesNotConsumeAnyRateLimit(): void
    {
        $component = $this->getMethodsComponent();
        $this->getQuote()->getPayment()->setMethod('banktransfer');

        $value = $component->getRepository()->getValue();

        $this->assertNotSame('banktransfer', $value);
        $this->assertSame($value, $this->getPersistedPayment()->getMethod());
        $this->assertSame(0, $this->processingRateLimiter->calls);
        $this->assertSame(0, $this->savingRateLimiter->calls);
    }

    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    #[ConfigFixture('payment/banktransfer/active', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/billing/select_default_payment_method', 1, 'store', 'default')]
    public function testRenderingTheCheckoutStillSavesTheDefaultMethodWhenTheLimitIsExceeded(): void
    {
        $component = $this->getMethodsComponent();
        $this->getQuote()->getPayment()->setMethod('banktransfer');
        $this->processingRateLimiter->exceeded = true;
        $this->savingRateLimiter->exceeded = true;

        $value = $component->getRepository()->getValue();

        $this->assertSame($value, $this->getPersistedPayment()->getMethod());
    }

    private function getMethodsComponent(): ComponentInterface
    {
        $this->saveShippingAddressValues(['country_id' => 'US', 'region' => 'Texas', 'postcode' => '78701']);
        $this->getCart();

        return $this->getCheckoutComponent('loki-checkout.payment.methods');
    }

    private function getPersistedPayment(): Payment
    {
        $quote = $this->getObjectManager()->create(Quote::class);
        $quote->loadByIdWithoutStore((int)$this->getCart()->getId());

        return $quote->getPayment();
    }
}
