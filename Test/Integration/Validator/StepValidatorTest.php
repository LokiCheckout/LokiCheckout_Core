<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Validator\Quote\ValidationRule\Email;
use LokiCheckout\Core\Validator\StepValidator;
use LokiCheckout\Core\ViewModel\CheckoutState;
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
class StepValidatorTest extends LokiCheckoutTestCase
{
    private function getCheckoutState(): CheckoutState
    {
        return $this->getObjectManager()->get(CheckoutState::class);
    }

    public function testEmptyRulesReturnsNoValidatorsFound(): void
    {
        $this->getCart();
        $subject = new StepValidator($this->getCheckoutState(), []);

        $result = $subject->validate();
        $this->assertSame(['No validators found'], $result);
    }

    public function testPassingRuleReturnsTrue(): void
    {
        $cart = $this->getCart();
        $cart->setCustomerEmail('valid@example.com');

        $emailRule = $this->getObjectManager()->get(Email::class);
        $subject = new StepValidator($this->getCheckoutState(), [$emailRule]);

        $this->assertTrue($subject->validate());
    }

    public function testFailingRuleReturnsErrors(): void
    {
        $cart = $this->getCart();
        $cart->setCustomerEmail('not-an-email');

        $emailRule = $this->getObjectManager()->get(Email::class);
        $subject = new StepValidator($this->getCheckoutState(), [$emailRule]);

        $result = $subject->validate();
        $this->assertIsArray($result);
        $this->assertStringContainsString('Email address is invalid', (string)$result[0]);
    }
}
