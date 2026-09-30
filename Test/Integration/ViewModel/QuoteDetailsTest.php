<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\ViewModel\QuoteDetails;
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
