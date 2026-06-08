<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\CustomerData\Checkout;

use LokiCheckout\Core\CustomerData\Checkout\BillingAddressDataCollector;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\AddressFactory;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class BillingAddressDataCollectorTest extends TestCase
{
    private BillingAddressDataCollector $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(BillingAddressDataCollector::class);
    }

    public function testGetDataMapsAddressFields(): void
    {
        $address = $this->createAddress();
        $address->setFirstname('Jane');
        $address->setLastname('Doe');
        $address->setCity('Amsterdam');
        $address->setPostcode('1111AA');
        $address->setTelephone('0123456789');

        $data = $this->subject->getData($address);

        $this->assertSame('Jane', $data['firstname']);
        $this->assertSame('Doe', $data['lastname']);
        $this->assertSame('Jane Doe', $data['fullname']);
        $this->assertSame('Amsterdam', $data['city']);
        $this->assertSame('1111AA', $data['postcode']);
        $this->assertSame('0123456789', $data['telephone']);
    }

    public function testFullnameIncludesMiddlename(): void
    {
        $address = $this->createAddress();
        $address->setFirstname('Jane');
        $address->setMiddlename('Q');
        $address->setLastname('Doe');

        $data = $this->subject->getData($address);
        $this->assertSame('Jane Q Doe', $data['fullname']);
    }

    private function createAddress(): Address
    {
        return ObjectManager::getInstance()->get(AddressFactory::class)->create();
    }
}
