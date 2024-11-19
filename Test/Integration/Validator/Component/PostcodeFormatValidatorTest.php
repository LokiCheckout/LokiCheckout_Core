<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Validator\Component;

use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress\Postcode;
use Yireo\LokiCheckout\Validator\Component\PostcodeFormatValidator;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class PostcodeFormatValidatorTest extends TestCase
{
    public function testValidateWithEmptyValues(): void
    {
        $validator = ObjectManager::getInstance()->get(PostcodeFormatValidator::class);
        $component = ObjectManager::getInstance()->get(Postcode::class);
        $addressState = ObjectManager::getInstance()->get(ShippingAddressState::class);
        $component->setAddressState($addressState);

        $this->assertTrue($validator->validate($component));
    }

    /**
     * @param string $postcode
     * @param string $countryId
     * @param bool $return
     * @return void
     * @dataProvider getValues
     */
    public function testValidateWithValues(string $postcode, string $countryId, bool $return): void
    {
        $validator = ObjectManager::getInstance()->get(PostcodeFormatValidator::class);
        $component = ObjectManager::getInstance()->get(Postcode::class);
        $component->value = $postcode;

        $addressState = ObjectManager::getInstance()->get(ShippingAddressState::class);
        $addressState->save('postcode', $postcode);
        $addressState->save('country_id', $countryId);
        $component->setAddressState($addressState);

        $this->assertSame($return, $validator->validate($component));
    }

    public function getValues(): array
    {
        return [
            ['1111 AA', 'NL', true],
            ['111AA', 'NL', false],
            ['11111', 'US', true],
            ['1111', 'US', false],
        ];
    }
}
