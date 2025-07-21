<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator\Component;

use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Validator\Component\CustomerAddressValidator;

#[AppArea('frontend')]
class CustomerAddressValidatorTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.city';

    public function testWithNoValue(): void
    {
        $validator = ObjectManager::getInstance()->get(CustomerAddressValidator::class);
        $this->assertTrue($validator->validate(null));
    }

    /**
     * @return void
     * @dataProvider getTestWithCityValues
     */
    public function testWithCity(string $city, bool $expectedResult, string $errorMessage): void
    {
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $validator = ObjectManager::getInstance()->get(CustomerAddressValidator::class);
        $result = $validator->validate($city, $component);

        if ($expectedResult) {
            $this->assertTrue($result);
        }

        if ($expectedResult === false) {
            $this->assertIsArray($result, 'Expected value "' . $city . '" to be invalid."');
            $message = array_pop($result);
            $this->assertStringContainsString($errorMessage, $message, var_export($result, true));
        }
    }

    public static function getTestWithCityValues(): array
    {
        return [
            ['Sin City', true, ''],
            ['Sin City 2', false, 'Invalid City'],
        ];
    }
}
