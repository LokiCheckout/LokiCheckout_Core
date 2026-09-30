<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Layout;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * AbstractLayoutTestCase::setUp() requires a cart fixture, but the cart does not need any products
 */
#[AppArea('frontend')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
class CoreBlockTest extends AbstractLayoutTestCase
{
    private const BLOCK_NAME = 'loki-checkout';

    public function testLokiCheckoutBlockExistsAfterLoadingLayout(): void
    {
        $layout = $this->getLayout();
        $block = $layout->getBlock(self::BLOCK_NAME);

        $this->assertInstanceOf(
            AbstractBlock::class,
            $block,
            'Block "' . self::BLOCK_NAME . '" was not found in the loaded layout'
        );
    }

    public function testNonExistentBlockDoesNotExistAfterLoadingLayout(): void
    {
        $layout = $this->getLayout();
        $this->assertFalse(
            $layout->getBlock('loki-checkout.non-existent-block'),
            'Unexpectedly found a non-existent block in the loaded layout'
        );
    }
}
