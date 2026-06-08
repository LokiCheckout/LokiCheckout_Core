<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator;

use LokiCheckout\Core\Validator\CustomerAddressValidator;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class CustomerAddressValidatorTest extends TestCase
{
    private CustomerAddressValidator $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(CustomerAddressValidator::class);
    }

    public function testValidAddressReturnsTrue(): void
    {
        $address = $this->createAddress([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'street' => ['Amalialaan 126'],
            'city' => 'Baarn',
            'postcode' => '3743KJ',
            'telephone' => '0123456789',
            'country_id' => 'NL',
        ]);

        $this->assertTrue($this->subject->validate($address));
    }

    public function testInvalidAddressReturnsErrors(): void
    {
        $address = $this->createAddress([
            'firstname' => '',
            'lastname' => '',
            'street' => [''],
            'city' => '',
            'telephone' => '',
            'country_id' => '',
        ]);

        $result = $this->subject->validate($address);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    private function createAddress(array $data): AddressInterface
    {
        $addressFactory = ObjectManager::getInstance()->get(AddressInterfaceFactory::class);
        $regionFactory = ObjectManager::getInstance()->get(RegionInterfaceFactory::class);

        /** @var AddressInterface $address */
        $address = $addressFactory->create();
        $address->setFirstname($data['firstname'] ?? null);
        $address->setLastname($data['lastname'] ?? null);
        $address->setStreet($data['street'] ?? ['']);
        $address->setCity($data['city'] ?? null);
        $address->setPostcode($data['postcode'] ?? null);
        $address->setTelephone($data['telephone'] ?? null);
        $address->setCountryId($data['country_id'] ?? null);
        $address->setRegion($regionFactory->create());

        return $address;
    }
}
