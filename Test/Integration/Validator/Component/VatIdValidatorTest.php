<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Validator\Component;

use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Postcode;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\VatId;
use Yireo\LokiCheckout\Validator\Component\PostcodeFormatValidator;
use Yireo\LokiCheckout\Validator\Component\VatIdValidator;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class VatIdValidatorTest extends TestCase
{
    public function testValidateWithEmptyValues(): void
    {
        $validator = ObjectManager::getInstance()->get(VatIdValidator::class);
        $component = ObjectManager::getInstance()->get(VatId::class);
        $addressState = ObjectManager::getInstance()->get(ShippingAddressState::class);
        $component->setAddressState($addressState);

        $this->assertTrue($validator->validate($component));
    }

    /**
     * @param string $vatId
     * @param string $countryId
     * @param bool $return
     * @return void
     * @dataProvider getValues
     */
    public function testValidateWithValues(string $vatId, string $countryId, bool $return): void
    {
        $validator = ObjectManager::getInstance()->get(VatIdValidator::class);
        $component = ObjectManager::getInstance()->get(VatId::class);
        $component->value = $vatId;

        $addressState = ObjectManager::getInstance()->get(ShippingAddressState::class);
        $addressState->save('country_id', $countryId);
        $component->setAddressState($addressState);

        $this->assertSame($return, $validator->validate($component));
    }

    public function getValues(): array
    {
        return [
            ['NL1234123412', 'NL', true],
            ['BE1234123412', 'NL', false],
            ['EL1234123412', 'GR', true],
            ['GR1234123412', 'GR', false],
        ];
    }
}
