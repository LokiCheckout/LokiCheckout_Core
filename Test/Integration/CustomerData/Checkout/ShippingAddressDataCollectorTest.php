<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\CustomerData\Checkout;

use LokiCheckout\Core\CustomerData\Checkout\ShippingAddressDataCollector;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\AddressFactory;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class ShippingAddressDataCollectorTest extends TestCase
{
    private ShippingAddressDataCollector $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(ShippingAddressDataCollector::class);
    }

    public function testGetDataMapsAddressFields(): void
    {
        $address = $this->createAddress();
        $address->setFirstname('John');
        $address->setLastname('Smith');
        $address->setCity('Rotterdam');
        $address->setCompany('ACME');

        $data = $this->subject->getData($address);

        $this->assertSame('John', $data['firstname']);
        $this->assertSame('Smith', $data['lastname']);
        $this->assertSame('John Smith', $data['fullname']);
        $this->assertSame('Rotterdam', $data['city']);
        $this->assertSame('ACME', $data['company']);
        $this->assertArrayHasKey('region', $data);
        $this->assertArrayHasKey('country', $data);
    }

    private function createAddress(): Address
    {
        return ObjectManager::getInstance()->get(AddressFactory::class)->create();
    }
}
