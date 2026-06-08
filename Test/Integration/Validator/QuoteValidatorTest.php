<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Validator\QuoteValidator;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\Exception\LocalizedException;
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
class QuoteValidatorTest extends LokiCheckoutTestCase
{
    public function testIncompleteQuoteThrowsValidationException(): void
    {
        $this->getCart();
        $subject = $this->getObjectManager()->get(QuoteValidator::class);

        $this->expectException(LocalizedException::class);
        $subject->validate();
    }
}
