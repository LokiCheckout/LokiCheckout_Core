<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Layout;

use LokiCheckout\Core\Test\Fixture\CheckoutStateFixture;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;


//#[DataFixture(CheckoutStateFixture::class, ['cart_id' => '$cart.id$'])]
#[DataFixture(ProductFixture::class, as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
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
