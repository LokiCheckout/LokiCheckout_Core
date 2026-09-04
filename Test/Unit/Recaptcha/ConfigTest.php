<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Recaptcha;

use LokiCheckout\Core\Recaptcha\Config;
use LokiCheckout\Core\Recaptcha\FormId;
use LokiCheckout\Core\Recaptcha\Type;
use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetTypeForReturnsNullWhenNothingIsConfigured(): void
    {
        $config = $this->createConfig([]);

        $this->assertNull($config->getTypeFor(FormId::PLACE_ORDER));
    }

    public function testGetTypeForReturnsNullForAnUnknownType(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_for/place_order' => 'something_else',
        ]);

        $this->assertNull($config->getTypeFor(FormId::PLACE_ORDER));
    }

    public function testGetTypeForReturnsTheConfiguredType(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_for/place_order' => Type::V3,
        ]);

        $this->assertSame(Type::V3, $config->getTypeFor(FormId::PLACE_ORDER));
    }

    public function testIsEnabledForRequiresBothKeys(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_for/place_order' => Type::CHECKBOX,
            'recaptcha_frontend/type_recaptcha/public_key' => 'site-key',
        ]);

        $this->assertFalse($config->isEnabledFor(FormId::PLACE_ORDER));
    }

    public function testIsEnabledForWithBothKeys(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_for/place_order' => Type::CHECKBOX,
            'recaptcha_frontend/type_recaptcha/public_key' => 'site-key',
            'recaptcha_frontend/type_recaptcha/private_key' => 'secret-key',
        ]);

        $this->assertTrue($config->isEnabledFor(FormId::PLACE_ORDER));
    }

    public function testGetActiveTypesReturnsUniqueTypesOfEnabledForms(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_for/place_order' => Type::V3,
            'recaptcha_frontend/type_for/customer_login' => Type::V3,
            'recaptcha_frontend/type_for/newsletter' => Type::CHECKBOX,
            'recaptcha_frontend/type_recaptcha_v3/public_key' => 'site-key',
            'recaptcha_frontend/type_recaptcha_v3/private_key' => 'secret-key',
            'recaptcha_frontend/type_recaptcha/public_key' => 'site-key',
            'recaptcha_frontend/type_recaptcha/private_key' => 'secret-key',
        ]);

        $this->assertSame([Type::V3, Type::CHECKBOX], $config->getActiveTypes());
    }

    public function testGetActiveTypesSkipsFormsWithoutKeys(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_for/place_order' => Type::V3,
        ]);

        $this->assertSame([], $config->getActiveTypes());
    }

    public function testGetRenderingSettingsForCheckbox(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_recaptcha/public_key' => 'site-key',
            'recaptcha_frontend/type_recaptcha/size' => 'compact',
            'recaptcha_frontend/type_recaptcha/theme' => 'dark',
            'recaptcha_frontend/type_recaptcha/lang' => 'nl',
        ]);

        $this->assertSame([
            'sitekey' => 'site-key',
            'theme' => 'dark',
            'hl' => 'nl',
            'size' => 'compact',
        ], $config->getRenderingSettings(Type::CHECKBOX));
    }

    public function testGetRenderingSettingsForInvisible(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_invisible/public_key' => 'site-key',
            'recaptcha_frontend/type_invisible/position' => 'bottomleft',
        ]);

        $settings = $config->getRenderingSettings(Type::INVISIBLE);

        $this->assertSame('invisible', $settings['size']);
        $this->assertSame('bottomleft', $settings['badge']);
    }

    public function testGetScoreThresholdFallsBackToTheDefault(): void
    {
        $config = $this->createConfig([]);

        $this->assertSame(0.5, $config->getScoreThreshold());
    }

    /**
     * @dataProvider getScoreThresholds
     */
    public function testGetScoreThresholdIsClamped(string $configured, float $expected): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/type_recaptcha_v3/score_threshold' => $configured,
        ]);

        $this->assertSame($expected, $config->getScoreThreshold());
    }

    public static function getScoreThresholds(): array
    {
        return [
            ['0.0', 0.1],
            ['0.3', 0.3],
            ['2.5', 1.0],
        ];
    }

    public function testFailureMessagesFallBackToDefaults(): void
    {
        $config = $this->createConfig([]);

        $this->assertNotSame('', $config->getValidationFailureMessage());
        $this->assertNotSame('', $config->getTechnicalFailureMessage());
    }

    public function testFailureMessagesUseTheConfiguredValue(): void
    {
        $config = $this->createConfig([
            'recaptcha_frontend/failure_messages/validation_failure_message' => 'Nope',
        ]);

        $this->assertSame('Nope', $config->getValidationFailureMessage());
    }

    /**
     * @param array<string, string> $values
     */
    private function createConfig(array $values): Config
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(
            static fn (string $path) => $values[$path] ?? null
        );

        return new Config($scopeConfig);
    }
}
