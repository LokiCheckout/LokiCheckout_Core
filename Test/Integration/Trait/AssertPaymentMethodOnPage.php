<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Trait;

trait AssertPaymentMethodOnPage
{
    protected function assertPaymentMethodOnPage(string $expectedMethod): void
    {
        $prefix = 'loki-checkout-payment-methods-field-';
        $string = $prefix.str_replace('_', '-', $expectedMethod);
        $this->assertStringOccursOnPage($string);
    }

    protected function assertPaymentMethodNotOnPage(string $expectedMethod): void
    {
        $prefix = 'loki-checkout-payment-methods-field-';
        $string = $prefix.str_replace('_', '-', $expectedMethod);
        $this->assertStringNotOccursOnPage($string);
    }
}
