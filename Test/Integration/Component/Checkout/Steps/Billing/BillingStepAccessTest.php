<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Steps\Billing;

use LokiCheckout\Core\Component\Checkout\Step\StepViewModelInterface;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Catalog\Test\Fixture\Virtual as VirtualProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[
    DbIsolation(true),
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(VirtualProductFixture::class, as: 'virtual'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$virtual.id$']),
    AppArea('frontend'),
]
final class BillingStepAccessTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.steps.billing';

    public function testBillingStepIsNotAccessibleForMixedCartWithoutValidShippingStep(): void
    {
        $quote = $this->getCart();
        $this->assertFalse($quote->isVirtual());
        $this->assertTrue($quote->hasVirtualItems());

        $viewModel = $this->getCheckoutComponent(self::BLOCK_NAME)->getViewModel();
        $this->assertInstanceOf(StepViewModelInterface::class, $viewModel);
        $this->assertFalse($viewModel->isAccessable());
    }
}
