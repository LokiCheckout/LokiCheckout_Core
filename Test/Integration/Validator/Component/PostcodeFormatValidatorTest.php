<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator\Component;

use Magento\Directory\Model\Country\Postcode\ConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\TestFramework\Fixture\AppArea;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Util\Component\AddressStateProvider;
use LokiCheckout\Core\Validator\Component\PostcodeFormatValidator;
use LokiCheckout\Core\ViewModel\State\AddressStateInterface;
use Loki\Components\Component\ComponentInterface;
use Loki\Components\Test\Integration\Traits\CreateComponent;

#[AppArea('frontend')]
class PostcodeFormatValidatorTest extends LokiCheckoutTestCase
{
    use CreateComponent;

    #[AppArea('frontend')]
    public function testValidateWithEmptyValues(): void
    {
        $component = $this->getPostcodeComponent();
        $postcodeFormatValidator = $this->getPostcodeFormatValidator('');

        $result = $postcodeFormatValidator->validate('', $component);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider getValues
     */
    public function testValidateWithValues(string $postcode, string $countryId, bool|string $expectedResult): void
    {
        $component = $this->getPostcodeComponent();
        $postcodeFormatValidator = $this->getPostcodeFormatValidator($countryId);

        $result = $postcodeFormatValidator->validate($postcode, $component);
        if ($expectedResult === true) {
            $this->assertTrue($result);
        }

        if (is_string($expectedResult)) {
            $this->assertStringContainsString($expectedResult, $result[0]);
        }
    }

    public function getValues(): array
    {
        return [
            ['1111 AA', 'NL', true],
            ['111AA', 'NL', 'needs to be in one of the following formats'],
            ['2222', 'NL', 'needs to be in one of the following formats'],
            ['11111', 'US', true],
            ['1111', 'US', 'needs to be in one of the following formats'],
        ];
    }

    private function getPostcodeComponent(): ComponentInterface
    {
        $block = $this->createMock(AbstractBlock::class);
        $block->method('getNameInLayout')->willReturn('billing.address.postcode');

        $component = $this->createMock(ComponentInterface::class);
        $component->method('getBlock')->willReturn($block);
        return $component;
    }

    private function getPostcodeFormatValidator(string $countryId): PostcodeFormatValidator
    {
        $addressState = $this->createMock(AddressStateInterface::class);
        $addressState->method('get')->willReturn($countryId);

        $addressStateProvider = $this->createMock(AddressStateProvider::class);
        $addressStateProvider->method('getAddressState')->willReturn($addressState);

        $postcodeConfig = ObjectManager::getInstance()->get(ConfigInterface::class);
        return new PostcodeFormatValidator($postcodeConfig, $addressStateProvider);
    }
}
