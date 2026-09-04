<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Recaptcha;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use LokiCheckout\Core\Recaptcha\Config;
use LokiCheckout\Core\Recaptcha\FormId;
use LokiCheckout\Core\Recaptcha\TokenValidator;
use LokiCheckout\Core\Recaptcha\Type;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class TokenValidatorTest extends TestCase
{
    private const VALIDATION_MESSAGE = 'Validation failed';
    private const TECHNICAL_MESSAGE = 'Technical failure';

    public function testAnUnconfiguredFormIsAlwaysValid(): void
    {
        $validator = $this->createValidator(null, [new Response(200, [], '{"success":false}')]);

        $this->assertTrue($validator->validate('token', FormId::PLACE_ORDER)->isValid());
    }

    public function testAnEmptyTokenIsInvalid(): void
    {
        $validator = $this->createValidator(Type::CHECKBOX, []);
        $result = $validator->validate('  ', FormId::PLACE_ORDER);

        $this->assertFalse($result->isValid());
        $this->assertSame(self::VALIDATION_MESSAGE, $result->getMessage());
    }

    public function testASuccessfulVerification(): void
    {
        $validator = $this->createValidator(Type::CHECKBOX, [
            new Response(200, [], '{"success":true}'),
        ]);

        $this->assertTrue($validator->validate('token', FormId::PLACE_ORDER)->isValid());
    }

    public function testAFailedVerification(): void
    {
        $validator = $this->createValidator(Type::CHECKBOX, [
            new Response(200, [], '{"success":false,"error-codes":["invalid-input-response"]}'),
        ]);

        $result = $validator->validate('token', FormId::PLACE_ORDER);

        $this->assertFalse($result->isValid());
        $this->assertSame(self::VALIDATION_MESSAGE, $result->getMessage());
    }

    public function testAMisconfiguredSecretIsATechnicalFailure(): void
    {
        $validator = $this->createValidator(Type::CHECKBOX, [
            new Response(200, [], '{"success":false,"error-codes":["invalid-input-secret"]}'),
        ]);

        $result = $validator->validate('token', FormId::PLACE_ORDER);

        $this->assertFalse($result->isValid());
        $this->assertSame(self::TECHNICAL_MESSAGE, $result->getMessage());
    }

    public function testAV3ScoreBelowTheThresholdIsInvalid(): void
    {
        $validator = $this->createValidator(Type::V3, [
            new Response(200, [], '{"success":true,"score":0.2}'),
        ]);

        $result = $validator->validate('token', FormId::PLACE_ORDER);

        $this->assertFalse($result->isValid());
        $this->assertSame(self::VALIDATION_MESSAGE, $result->getMessage());
    }

    public function testAV3ScoreAboveTheThresholdIsValid(): void
    {
        $validator = $this->createValidator(Type::V3, [
            new Response(200, [], '{"success":true,"score":0.9}'),
        ]);

        $this->assertTrue($validator->validate('token', FormId::PLACE_ORDER)->isValid());
    }

    public function testATransportErrorIsATechnicalFailure(): void
    {
        $validator = $this->createValidator(Type::CHECKBOX, [
            new ConnectException('Connection refused', new Request('POST', 'https://www.google.com')),
        ]);

        $result = $validator->validate('token', FormId::PLACE_ORDER);

        $this->assertFalse($result->isValid());
        $this->assertSame(self::TECHNICAL_MESSAGE, $result->getMessage());
    }

    public function testAnUnreadableResponseIsATechnicalFailure(): void
    {
        $validator = $this->createValidator(Type::CHECKBOX, [
            new Response(200, [], 'not json'),
        ]);

        $result = $validator->validate('token', FormId::PLACE_ORDER);

        $this->assertFalse($result->isValid());
        $this->assertSame(self::TECHNICAL_MESSAGE, $result->getMessage());
    }

    private function createValidator(?string $type, array $queue): TokenValidator
    {
        $config = $this->createMock(Config::class);
        $config->method('getTypeFor')->willReturn($type);
        $config->method('getSecretKey')->willReturn('secret-key');
        $config->method('getScoreThreshold')->willReturn(0.5);
        $config->method('getValidationFailureMessage')->willReturn(self::VALIDATION_MESSAGE);
        $config->method('getTechnicalFailureMessage')->willReturn(self::TECHNICAL_MESSAGE);

        $remoteAddress = $this->createMock(RemoteAddress::class);
        $remoteAddress->method('getRemoteAddress')->willReturn('127.0.0.1');

        $client = new Client(['handler' => HandlerStack::create(new MockHandler($queue))]);

        return new TokenValidator($client, $config, $remoteAddress, $this->createMock(LoggerInterface::class));
    }
}
