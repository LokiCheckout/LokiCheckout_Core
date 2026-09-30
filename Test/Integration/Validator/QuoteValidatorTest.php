<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Validator\QuoteValidator;
use Magento\Framework\Exception\LocalizedException;
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
