<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Validator\Component;

use Magento\Customer\Model\Vat as VatModel;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\Config;
use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\VatId;
use Yireo\LokiCheckout\Validator\Component\VatIdOnlineValidator;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class VatIdOnlineValidatorTest extends TestCase
{
    public function testValidateWithCorrectGatewayResponse(): void
    {
        $validator = ObjectManager::getInstance()->create(VatIdOnlineValidator::class, [
            'vatModel' => $this->getVatModelMock($this->getValidatedGatewayResponse())
        ]);

        $component = ObjectManager::getInstance()->get(VatId::class);
        $component->value = 'NL1234123412';

        $addressState = ObjectManager::getInstance()->get(ShippingAddressState::class);
        $addressState->save('country_id', 'NL');
        $component->setAddressState($addressState);

        $this->assertTrue($validator->validate($component));
    }

    public function testValidateWithInvalidGatewayResponse(): void
    {
        $validator = ObjectManager::getInstance()->create(VatIdOnlineValidator::class, [
            'vatModel' => $this->getVatModelMock($this->getFailedGatewayResponse())
        ]);

        $component = ObjectManager::getInstance()->get(VatId::class);
        $component->value = 'NL1234123412';

        $addressState = ObjectManager::getInstance()->get(ShippingAddressState::class);
        $addressState->save('country_id', 'NL');
        $component->setAddressState($addressState);

        $this->assertFalse($validator->validate($component));
    }

    private function getVatModelMock(DataObject $gatewayResponse): VatModel
    {
        $vatModel = $this->createMock(VatModel::class);
        $vatModel->method('getMerchantCountryCode')->willReturn('NL');
        $vatModel->method('getMerchantVatNumber')->willReturn('NL12341234');
        $vatModel->method('isCountryInEU')->willReturn(true);
        $vatModel->method('checkVatNumber')->willReturn($gatewayResponse);
        return $vatModel;
    }

    private function getFailedGatewayResponse(): DataObject
    {
        return new DataObject([
            'is_valid' => false,
            'request_date' => '',
            'request_identifier' => '',
            'request_success' => false,
            'request_message' => __('Error during VAT Number verification.'),
        ]);
    }

    private function getValidatedGatewayResponse(): DataObject
    {
        return new DataObject([
            'is_valid' => true,
            'request_date' => '',
            'request_identifier' => '',
            'request_success' => true,
            'request_message' => __('Error during VAT Number verification.'),
        ]);
    }
}
