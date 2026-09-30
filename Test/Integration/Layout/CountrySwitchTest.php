<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Layout;

use LokiCheckout\Core\Test\Fixture\CheckoutStateFixture;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;


//#[DataFixture(CheckoutStateFixture::class, ['cart_id' => '$cart.id$'])]
/**
 * The cart does not need any products for these tests, so no product fixtures are used (saving time)
 */
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[AppArea('frontend')]
class CountrySwitchTest extends AbstractLayoutTestCase
{
    private const COUNTRY_BLOCK_NAME = 'loki-checkout.shipping.address.country_id';
    private const POSTCODE_BLOCK_NAME = 'loki-checkout.shipping.address.postcode';

    public function testForCountry(): void
    {
        $layout = $this->getLayout();

        $countryBlock = $layout->getBlock(self::COUNTRY_BLOCK_NAME);
        $this->assertInstanceOf(AbstractBlock::class, $countryBlock);

        $postcodeBlock = $layout->getBlock(self::POSTCODE_BLOCK_NAME);
        $this->assertInstanceOf(AbstractBlock::class, $postcodeBlock);
    }
}
