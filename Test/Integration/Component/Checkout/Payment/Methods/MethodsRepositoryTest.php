<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Payment\Methods;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Test\Integration\Trait\SaveShippingAddressValues;
use Loki\Components\Component\ComponentRepositoryInterface;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Payment;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[DbIsolation(true)]
#[DataFixture(ProductFixture::class, as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
class MethodsRepositoryTest extends LokiCheckoutTestCase
{
    use SaveShippingAddressValues;

    private const BLOCK_NAME = 'loki-checkout.payment.methods';

    #[ConfigFixture('payment/paypal_express/active', 1, 'store', 'default')]
    #[ConfigFixture('payment/paypal_express/in_context', 0, 'store', 'default')]
    #[ConfigFixture('paypal/general/merchant_country', 'US', 'store', 'default')]
    #[ConfigFixture('paypal/general/business_account', 'merchant@example.com', 'store', 'default')]
    public function testSavingPaypalExpressDoesNotStoreTransferData(): void
    {
        if (false === $this->getObjectManager()->get(ModuleManager::class)->isEnabled('Magento_Paypal')) {
            $this->markTestSkipped('Module Magento_Paypal is not enabled');
        }

        $this->getMethodsRepository()->saveValue('paypal_express');

        $payment = $this->getPersistedPayment();
        $this->assertSame('paypal_express', $payment->getMethod());
        $this->assertArrayNotHasKey('method_instance', (array)$payment->getAdditionalInformation());
        $this->assertArrayNotHasKey('quote_id', (array)$payment->getAdditionalInformation());
    }

    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    public function testSavingCheckmoDoesNotStoreTransferData(): void
    {
        $this->getMethodsRepository()->saveValue('checkmo');

        $payment = $this->getPersistedPayment();
        $this->assertSame('checkmo', $payment->getMethod());
        $this->assertArrayNotHasKey('method_instance', (array)$payment->getAdditionalInformation());
        $this->assertArrayNotHasKey('quote_id', (array)$payment->getAdditionalInformation());
    }

    private function getMethodsRepository(): ComponentRepositoryInterface
    {
        $this->saveShippingAddressValues(['country_id' => 'US', 'region' => 'Texas', 'postcode' => '78701']);
        $this->getCart();

        $repository = $this->getCheckoutComponent(self::BLOCK_NAME)->getRepository();
        $this->assertInstanceOf(ComponentRepositoryInterface::class, $repository);

        return $repository;
    }

    private function getPersistedPayment(): Payment
    {
        $quote = $this->getObjectManager()->create(Quote::class);
        $quote->loadByIdWithoutStore((int)$this->getCart()->getId());
        $this->assertNotEmpty($quote->getId());

        return $quote->getPayment();
    }
}
