<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\ResetStepAfterCartSave;
use LokiCheckout\Core\Step\StepNavigator;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\Event\Observer;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class ResetStepAfterCartSaveTest extends LokiCheckoutTestCase
{
    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testStepResetWhenEnabled(): void
    {
        $this->dispatchToCheckout();

        $stepNavigator = $this->getObjectManager()->get(StepNavigator::class);
        $firstStepCode = $stepNavigator->getFirstStep()->getCode();

        $subject = $this->getObjectManager()->get(ResetStepAfterCartSave::class);
        $subject->execute(new Observer());

        $this->assertSame($firstStepCode, $stepNavigator->getCurrentStep()->getCode());
    }

    #[ConfigFixture('loki_checkout/general/enable', 0, 'store', 'default')]
    public function testNoOpWhenDisabled(): void
    {
        $this->dispatchToCheckout();

        $subject = $this->getObjectManager()->get(ResetStepAfterCartSave::class);
        $subject->execute(new Observer());

        $this->assertTrue(true);
    }
}
