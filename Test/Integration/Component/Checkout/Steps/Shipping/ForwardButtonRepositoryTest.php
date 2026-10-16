<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Steps\Shipping;

use LokiCheckout\Core\Step\StepNavigator;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Loki\Components\Util\SkipValidation;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[
    DbIsolation(true),
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
final class ForwardButtonRepositoryTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.steps.shipping.forward-button';

    public function testValidationIsNotSkippedWhenStepIsInvalid(): void
    {
        $this->getCart();

        $skipValidation = $this->getObjectManager()->get(SkipValidation::class);
        $skipValidation->setSkipValidation(false);

        $stepNavigator = $this->getObjectManager()->get(StepNavigator::class);
        $this->assertSame('shipping', $stepNavigator->getCurrentStep()->getCode());

        $this->getCheckoutComponent(self::BLOCK_NAME)->getRepository()->saveValue(true);

        $this->assertSame('shipping', $stepNavigator->getCurrentStep()->getCode());
        $this->assertFalse($skipValidation->isSkipValidation());
    }
}
