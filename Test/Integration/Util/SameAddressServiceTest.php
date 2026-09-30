<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Util\SameAddressService;
use Magento\Quote\Model\Quote;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * The cart does not need any products for these tests, so no product fixtures are used (saving time)
 */
#[
    DataFixture(GuestCartFixture::class, as: 'cart'),
    AppArea('frontend'),
]
class SameAddressServiceTest extends LokiCheckoutTestCase
{
    private function getSubject(): SameAddressService
    {
        return $this->getObjectManager()->get(SameAddressService::class);
    }

    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')]
    public function testIsEnabled(): void
    {
        $this->assertTrue($this->getSubject()->isEnabled());
    }

    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', 0, 'store', 'default')]
    public function testIsNotEnabledAndNotSame(): void
    {
        $this->assertFalse($this->getSubject()->isEnabled());
        $this->assertFalse($this->getSubject()->isSame());
    }

    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')]
    public function testCopyBillingToShipping(): void
    {
        $cart = $this->getCart();
        $this->prepareSameQuote($cart);

        $cart->getBillingAddress()
            ->setFirstname('Billing')
            ->setLastname('Person')
            ->setCity('Amsterdam')
            ->setCountryId('NL');

        $this->getSubject()->copyBillingToShipping();

        $shippingAddress = $cart->getShippingAddress();
        $this->assertSame('Billing', $shippingAddress->getFirstname());
        $this->assertSame('Person', $shippingAddress->getLastname());
        $this->assertSame('Amsterdam', $shippingAddress->getCity());
    }

    private function prepareSameQuote(Quote $cart): void
    {
        $this->assertFalse($cart->isVirtual());
        $cart->getShippingAddress()->setSameAsBilling(true);
        $this->assertTrue($this->getSubject()->isSame());
    }
}
