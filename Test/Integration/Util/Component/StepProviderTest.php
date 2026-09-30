<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Component;

use LokiCheckout\Core\Step\StepNavigator;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Util\Component\StepProvider;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * The cart does not need any products for these tests, so no product fixtures are used (saving time)
 */
#[
    DataFixture(GuestCartFixture::class, as: 'cart'),
    AppArea('frontend'),
]
class StepProviderTest extends LokiCheckoutTestCase
{
    public function testGetStepForCheckoutBlockResolvesToKnownStep(): void
    {
        $this->getCart();

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
