<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Layout;

use Loki\Components\Exception\RedirectException;
use LokiCheckout\Core\Layout\CountrySpecificLayoutHandler;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use PHPUnit\Framework\TestCase;

class CountrySpecificLayoutHandlerTest extends TestCase
{
    private const BASE_HANDLES = [
        'loki_checkout',
        'loki_checkout_skin_default',
        'loki_checkout_address_default_grid',
    ];

    public function testPhysicalQuoteWithShippingAndBillingCountry(): void
    {
        $handles = $this->getHandler('NL', 'BE', false)->get(self::BASE_HANDLES);

        $this->assertContains('loki_checkout_country_nl', $handles);
        $this->assertContains('loki_checkout_shipping_address_country_nl', $handles);
        $this->assertContains('loki_checkout_billing_address_country_be', $handles);
        $this->assertContains('loki_checkout_skin_default_shipping_address_country_nl', $handles);
        $this->assertContains('loki_checkout_skin_default_billing_address_country_be', $handles);
        $this->assertContains('loki_checkout_address_default_grid_billing_address_country_be', $handles);
    }

    public function testPhysicalQuoteWithoutBillingCountryFallsBackToShippingCountry(): void
    {
        $handles = $this->getHandler('NL', '', false)->get(self::BASE_HANDLES);

        $this->assertContains('loki_checkout_shipping_address_country_nl', $handles);
        $this->assertContains('loki_checkout_billing_address_country_nl', $handles);
    }

    public function testVirtualQuoteWithOnlyBillingCountry(): void
    {
        $handles = $this->getHandler('', 'NL', true)->get(self::BASE_HANDLES);

        $this->assertContains('loki_checkout_country_nl', $handles);
        $this->assertContains('loki_checkout_billing_address_country_nl', $handles);
        $this->assertContains('loki_checkout_skin_default_billing_address_country_nl', $handles);
        $this->assertContains('loki_checkout_address_default_grid_billing_address_country_nl', $handles);
        $this->assertEmpty(preg_grep('/shipping_address_country_/', $handles));
    }

    public function testVirtualQuoteIgnoresShippingCountry(): void
    {
        $handles = $this->getHandler('US', 'NL', true)->get(self::BASE_HANDLES);

        $this->assertContains('loki_checkout_country_nl', $handles);
        $this->assertContains('loki_checkout_billing_address_country_nl', $handles);
        $this->assertNotContains('loki_checkout_country_us', $handles);
        $this->assertEmpty(preg_grep('/shipping_address_country_/', $handles));
    }

    public function testNoCountriesReturnsHandlesUnchanged(): void
    {
        $handles = $this->getHandler('', '', true)->get(self::BASE_HANDLES);

        $this->assertSame(self::BASE_HANDLES, $handles);
    }

    public function testExistingCountryHandlesAreReplaced(): void
    {
        $existingHandles = array_merge(self::BASE_HANDLES, ['loki_checkout_billing_address_country_de']);
        $handles = $this->getHandler('', 'NL', true)->get($existingHandles);

        $this->assertNotContains('loki_checkout_billing_address_country_de', $handles);
        $this->assertContains('loki_checkout_billing_address_country_nl', $handles);
    }

    public function testNoQuoteReturnsHandlesUnchanged(): void
    {
        $checkoutState = $this->createMock(CheckoutState::class);
        $checkoutState->method('getQuote')->willThrowException(new RedirectException('No quote'));

        $handler = new CountrySpecificLayoutHandler($checkoutState);

        $this->assertSame(self::BASE_HANDLES, $handler->get(self::BASE_HANDLES));
    }

    private function getHandler(
        string $shippingCountryId,
        string $billingCountryId,
        bool $isVirtual
    ): CountrySpecificLayoutHandler {
        $shippingAddress = $this->createMock(Address::class);
        $shippingAddress->method('getCountryId')->willReturn($shippingCountryId);

        $billingAddress = $this->createMock(Address::class);
        $billingAddress->method('getCountryId')->willReturn($billingCountryId);

        $quote = $this->createMock(Quote::class);
        $quote->method('isVirtual')->willReturn($isVirtual);
        $quote->method('getShippingAddress')->willReturn($shippingAddress);
        $quote->method('getBillingAddress')->willReturn($billingAddress);

        $checkoutState = $this->createMock(CheckoutState::class);
        $checkoutState->method('getQuote')->willReturn($quote);

        return new CountrySpecificLayoutHandler($checkoutState);
    }
}
