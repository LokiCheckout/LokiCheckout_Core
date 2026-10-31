<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step;

use Loki\Components\Exception\RedirectException;
use LokiCheckout\Core\Step\FinalStep;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Test\Integration\Trait\RateLimiterStubs;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[
    DbIsolation(true),
    DataFixture(ProductFixture::class, ['price' => 10], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class FinalStepRateLimitTest extends LokiCheckoutTestCase
{
    use RateLimiterStubs;

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

    public function testExceededPaymentProcessingRateLimitRedirectsBackToTheCheckout(): void
    {
        $this->getCart();
        $this->processingRateLimiter->exceeded = true;

        try {
            $this->getObjectManager()->create(FinalStep::class)->submit();
            $this->fail('Expected a redirect');
        } catch (RedirectException $exception) {
            $this->assertStringEndsWith('/checkout/', $exception->getUrl());
        }

        $this->assertSame(1, $this->processingRateLimiter->calls);
        $errors = array_map(
            fn ($message) => $message->getText(),
            $this->getObjectManager()->get(MessageManager::class)->getMessages()->getErrors()
        );
        $this->assertContains('Rate limit exceeded', $errors);
    }
}
