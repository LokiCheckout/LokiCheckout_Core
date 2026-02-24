<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Validator\Component;

use LokiCheckout\Core\Validator\Component\IbanValidator;
use PHPUnit\Framework\TestCase;

class IbanValidatorTest extends TestCase
{
    /**
     * @param string     $ibanNumber
     * @param bool|array $expectedResult
     *
     * @return void
     * @dataProvider getTestValues
     */
    public function testValidate(string $ibanNumber, bool|array $expectedResult, string $expectedMessage = '')
    {
        $ibanValidator = new IbanValidator();
        $actualResult = $ibanValidator->validate($ibanNumber);
        if ($actualResult === true) {
            $this->assertTrue($expectedResult);
            return;
        }

        $this->assertIsArray($actualResult);

        $messages = [];
        foreach ($actualResult as $message) {
            $messages[] = (string)$message;
        }

        $this->assertContains($expectedMessage, $messages);
    }

    public static function getTestValues()
    {
        return [
            ['GB82WEST12345698765432', true],
            ['DE44500105175407324931', true],
            ['NL91ABNA0417164300', true],
            ['GB82WEST12345698765433', false, 'Your IBAN number is incorrect'],
        ];
    }
}
