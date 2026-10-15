<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step;

use Loki\Components\Exception\RedirectException;
use LokiCheckout\Core\Step\FinalStep;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Test\Integration\Trait\SaveShippingAddressValues;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(ProductFixture::class, ['price' => 10], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class FinalStepTest extends LokiCheckoutTestCase
{
    use SaveShippingAddressValues;

    private function getSubject(): FinalStep
    {
        return $this->getObjectManager()->get(FinalStep::class);
    }

    #[ConfigFixture('carriers/flatrate/active', 1, 'store', 'default')]
    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    public function testValidateThrowsForIncompleteQuote(): void
    {
        $this->getCart();

        $this->expectException(LocalizedException::class);
        $this->getSubject()->validate();
    }

    #[ConfigFixture('carriers/flatrate/active', 1, 'store', 'default')]
    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    public function testValidatePassesForCompleteQuote(): void
    {
        $this->dispatchToCheckout();
        $this->saveShippingAddressValues();
        $this->prepareQuoteForSubmit();

        $this->assertTrue($this->getSubject()->validate());
    }

    #[ConfigFixture('carriers/flatrate/active', 1, 'store', 'default')]
    #[ConfigFixture('payment/checkmo/active', 1, 'store', 'default')]
    #[ConfigFixture('checkout/options/guest_checkout', 0, 'store', 'default')]
    public function testGuestCanNotPlaceOrderWhenGuestCheckoutIsDisabled(): void
    {
        $this->getCart();
        $this->prepareQuoteForSubmit();

        try {
            $this->getSubject()->submit();
            $this->fail('Expected a redirect');
        } catch (RedirectException $redirectException) {
            $this->assertStringContainsString('checkout/cart', $redirectException->getUrl());
        }

        $quote = $this->getObjectManager()->get(CartRepositoryInterface::class)->get($this->getQuote()->getId());
        $this->assertTrue((bool)$quote->getIsActive());
        $this->assertEmpty($quote->getReservedOrderId());
    }

    private function prepareQuoteForSubmit(): Quote
    {
        $quote = $this->getQuote();

        $addressData = [
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'street' => ['Testing Street 12'],
            'city' => 'Amsterdam',
            'postcode' => '1111AA',
            'country_id' => 'NL',
            'telephone' => '0123456789',
            'email' => 'janedoe@example.org',
        ];

        $quote->getBillingAddress()->addData($addressData);

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->addData($addressData);
        $shippingAddress->setShippingMethod('flatrate_flatrate');
        $shippingAddress->setCollectShippingRates(true);
        $shippingAddress->collectShippingRates();

        $quote->setCustomerEmail('janedoe@example.org');
        $quote->getPayment()->setMethod('checkmo');

        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();

        $cartRepository = $this->getObjectManager()->get(CartRepositoryInterface::class);
        $cartRepository->save($quote);

        return $quote;
    }
}
