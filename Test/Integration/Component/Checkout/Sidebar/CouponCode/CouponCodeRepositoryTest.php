<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Sidebar\CouponCode;

use LokiCheckout\Core\Component\Checkout\Billing\PaymentMethods\PaymentMethodsRepository;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Component\ComponentRepositoryInterface;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\OfflineShipping\Model\SalesRule\Rule as FreeShippingRule;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Test\Fixture\Rule as RuleFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[DbIsolation(true)]
#[AppArea('frontend')]
final class CouponCodeRepositoryTest extends LokiCheckoutTestCase
{
    private const BLOCK_NAME = 'loki-checkout.sidebar.coupon_code';

    #[ConfigFixture('carriers/flatrate/active', 1, 'store', 'default')]
    #[ConfigFixture('carriers/flatrate/price', 5, 'store', 'default')]
    #[DataFixture(ProductFixture::class, ['price' => 10], as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[DataFixture(RuleFixture::class, [
        'coupon_type' => Rule::COUPON_TYPE_SPECIFIC,
        'coupon_code' => 'FREESHIP',
        'simple_action' => Rule::BY_PERCENT_ACTION,
        'discount_amount' => 0,
        'simple_free_shipping' => FreeShippingRule::FREE_SHIPPING_ITEM,
    ])]
    public function testFreeShippingCouponRecalculatesShippingWhenAppliedAndRemoved(): void
    {
        $this->prepareQuoteWithFlatRate();
        $this->assertEquals(5, $this->getQuote()->getShippingAddress()->getShippingAmount());

        $this->getRepository()->saveValue(['task' => 'apply', 'code' => 'FREESHIP']);
        $this->assertSame('FREESHIP', $this->getQuote()->getCouponCode());
        $this->assertEquals(0, $this->getQuote()->getShippingAddress()->getShippingAmount());

        $this->getRepository()->saveValue(['task' => 'remove', 'code' => '']);
        $this->assertEmpty($this->getQuote()->getCouponCode());
        $this->assertEquals(5, $this->getQuote()->getShippingAddress()->getShippingAmount());
    }

    #[ConfigFixture('carriers/flatrate/active', 1, 'store', 'default')]
    #[ConfigFixture('carriers/flatrate/price', 5, 'store', 'default')]
    #[ConfigFixture('payment/free/active', 1, 'store', 'default')]
    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    #[DataFixture(ProductFixture::class, ['price' => 10], as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[DataFixture(RuleFixture::class, [
        'coupon_type' => Rule::COUPON_TYPE_SPECIFIC,
        'coupon_code' => 'FREEORDER',
        'simple_action' => Rule::BY_PERCENT_ACTION,
        'discount_amount' => 100,
        'apply_to_shipping' => 1,
    ])]
    public function testFullDiscountCouponRecalculatesGrandTotalWhenAppliedAndRemoved(): void
    {
        $this->prepareQuoteWithFlatRate();
        $this->assertEquals(15, $this->getQuote()->getGrandTotal());

        $this->getRepository()->saveValue(['task' => 'apply', 'code' => 'FREEORDER']);
        $this->assertEquals(0, $this->getQuote()->getGrandTotal());
        $this->getObjectManager()->get(CheckoutState::class)->saveQuote();

        $paymentMethodsRepository = $this->getCheckoutComponent('loki-checkout.payment.methods')->getRepository();
        $this->assertInstanceOf(PaymentMethodsRepository::class, $paymentMethodsRepository);
        $paymentMethodCodes = array_map(
            fn ($paymentMethod) => $paymentMethod->getCode(),
            $paymentMethodsRepository->getPaymentMethods()
        );
        $this->assertSame(['free'], $paymentMethodCodes);
        $this->assertSame('free', $paymentMethodsRepository->getValue());

        $this->getRepository()->saveValue(['task' => 'remove', 'code' => '']);
        $this->assertEquals(15, $this->getQuote()->getGrandTotal());
    }

    private function prepareQuoteWithFlatRate(): void
    {
        $quote = $this->getCart();
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->addData([
            'email' => 'janedoe@example.org',
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'street' => 'Testing Street 12',
            'postcode' => '1111AA',
            'city' => 'Amsterdam',
            'country_id' => 'NL',
            'telephone' => '0612345678',
        ]);
        $shippingAddress->setCollectShippingRates(true);
        $shippingAddress->collectShippingRates();
        $shippingAddress->setShippingMethod('flatrate_flatrate');
        $quote->collectTotals();
        $this->getObjectManager()->get(CartRepositoryInterface::class)->save($quote);
    }

    private function getRepository(): ComponentRepositoryInterface
    {
        $repository = $this->getCheckoutComponent(self::BLOCK_NAME)->getRepository();
        $this->assertInstanceOf(ComponentRepositoryInterface::class, $repository);

        return $repository;
    }
}
