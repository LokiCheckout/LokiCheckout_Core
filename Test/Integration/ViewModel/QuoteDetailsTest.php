<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\ViewModel\QuoteDetails;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
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
class QuoteDetailsTest extends LokiCheckoutTestCase
{
    private function getSubject(): QuoteDetails
    {
        return $this->getObjectManager()->get(QuoteDetails::class);
    }

    public function testGetEmailAndGender(): void
    {
        $cart = $this->getCart();
        $cart->setCustomerEmail('detail@example.com');
        $cart->setCustomerGender('male');

        $this->assertSame('detail@example.com', $this->getSubject()->getEmail());
        $this->assertSame('male', $this->getSubject()->getGender());
    }

    public function testGetDobFormatsDate(): void
    {
        $cart = $this->getCart();
        $cart->setCustomerDob('1980-05-15 00:00:00');

        $this->assertSame('1980-05-15', $this->getSubject()->getDob());
    }

    public function testGetDobReturnsEmptyForEmptyDob(): void
    {
        $cart = $this->getCart();
        $cart->setCustomerDob('');

        $this->assertSame('', $this->getSubject()->getDob());
    }
}
