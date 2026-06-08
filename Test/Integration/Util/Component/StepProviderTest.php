<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Component;

use LokiCheckout\Core\Step\StepNavigator;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Util\Component\StepProvider;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class StepProviderTest extends LokiCheckoutTestCase
{
    public function testGetStepForCheckoutBlockResolvesToKnownStep(): void
    {
        $this->dispatchToCheckout();

        $component = $this->getCheckoutComponent('loki-checkout.shipping.address.firstname');
        $block = $component->getBlock();
        $this->assertInstanceOf(AbstractBlock::class, $block);

        $subject = $this->getObjectManager()->get(StepProvider::class);
        $step = $subject->getStep($block);

        $this->assertIsString($step);
        $this->assertNotSame('', $step);

        $stepNavigator = $this->getObjectManager()->get(StepNavigator::class);
        $validCodes = ['none'];
        foreach ($stepNavigator->getSteps() as $stepViewModel) {
            $validCodes[] = $stepViewModel->getCode();
        }

        $this->assertContains($step, $validCodes);
    }
}
