<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Payment;

use LokiCheckout\Core\Payment\ApplePayRequest;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
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
class ApplePayRequestTest extends LokiCheckoutTestCase
{
    private function getSubject(): ApplePayRequest
    {
        return $this->getObjectManager()->get(ApplePayRequest::class);
    }

    public function testStaticCapabilitiesAndNetworks(): void
    {
        $this->getCart();
        $subject = $this->getSubject();

        $this->assertContains('supports3DS', $subject->getMerchantCapabilities());
        $this->assertContains('visa', $subject->getSupportedNetworks());
    }

    public function testRequiredBillingContactFieldsForPhysicalQuote(): void
    {
        $this->getCart();
        $fields = $this->getSubject()->getRequiredBillingContactFields();

        $this->assertContains('billingAddress', $fields);
        $this->assertContains('postalAddress', $fields);
    }

    public function testToJsonShape(): void
    {
        $cart = $this->getCart();
        $cart->getBillingAddress()->setCountryId('NL');

        $json = $this->getSubject()->toJson();

        $this->assertArrayHasKey('countryCode', $json);
        $this->assertArrayHasKey('currencyCode', $json);
        $this->assertArrayHasKey('merchantCapabilities', $json);
        $this->assertArrayHasKey('supportedNetworks', $json);
        $this->assertArrayHasKey('requiredBillingContactFields', $json);
        $this->assertArrayHasKey('total', $json);
        $this->assertSame('NL', $json['countryCode']);
    }
}
