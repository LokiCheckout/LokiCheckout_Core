<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Component\Base\Payment\ApplePay;

use LokiCheckout\Core\Component\Base\Payment\ApplePay\ApplePayRepository;
use PHPUnit\Framework\TestCase;

class ApplePayRepositoryTest extends TestCase
{
    /**
     * @dataProvider getValidationUrls
     */
    public function testOnlyAppleValidationUrlsAreAccepted(string $validationUrl, bool $expected): void
    {
        $repository = new class extends ApplePayRepository {
            public ?string $validatedUrl = null;

            protected function onValidateMerchant(string $validationUrl, string $originDomain): void
            {
                $this->validatedUrl = $validationUrl;
            }
        };

        $repository->saveValue([
            'task' => 'onValidateMerchant',
            'validationUrl' => $validationUrl,
            'originDomain' => 'example.com',
        ]);

        $this->assertSame($expected, $repository->validatedUrl === $validationUrl);
    }

    public function testMissingValuesAreIgnored(): void
    {
        $repository = new class extends ApplePayRepository {
            public bool $called = false;

            protected function onValidateMerchant(string $validationUrl, string $originDomain): void
            {
                $this->called = true;
            }
        };

        $repository->saveValue(['task' => 'onValidateMerchant']);
        $this->assertFalse($repository->called);
    }

    public static function getValidationUrls(): array
    {
        return [
            ['https://apple-pay-gateway.apple.com/paymentservices/startSession', true],
            ['https://apple-pay-gateway-nc-pod5.apple.com/paymentservices/paymentSession', true],
            ['https://cn-apple-pay-gateway.apple.com/paymentservices/paymentSession', true],
            ['http://apple-pay-gateway.apple.com/paymentservices/startSession', false],
            ['https://apple-pay-gateway.apple.com.evil.example/', false],
            ['https://evil.example/?apple-pay-gateway.apple.com', false],
            ['https://user@apple-pay-gateway.apple.com/', false],
            ['https://apple-pay-gateway.apple.com:8080/', false],
            ['http://169.254.169.254/latest/meta-data/', false],
            ['file:///etc/passwd', false],
        ];
    }
}
