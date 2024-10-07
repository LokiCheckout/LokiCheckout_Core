<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Test\Integration\Validator;

use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use Yireo\MagewireCheckout\Validator\PostcodeValidator;

class PostcodeValidatorTest extends TestCase
{
    public function testValidateWithEmptyValues(): void
    {
        $postcodeValidator = ObjectManager::getInstance()->get(PostcodeValidator::class);
        $this->assertTrue($postcodeValidator->validate());
    }

    /**
     * @param string $postcode
     * @param string $countryId
     * @param true|array $return
     * @return void
     * @dataProvider getValues
     */
    public function testValidateWithValues(string $postcode, string $countryId, true|array $return): void
    {
        $postcodeValidator = ObjectManager::getInstance()->get(PostcodeValidator::class);
        $postcodeValidator->setPostcode($postcode)->setCountryId($countryId);

        if ($return === true) {
            $this->assertTrue($postcodeValidator->validate());
            return;
        }

        $this->assertIsArray($postcodeValidator->validate());
    }

    public function getValues(): array
    {
        return [
            ['1111 AA', 'NL', true],
            ['111AA', 'NL', []],
            ['11111', 'US', true],
            ['1111', 'US', []],
        ];
    }
}
