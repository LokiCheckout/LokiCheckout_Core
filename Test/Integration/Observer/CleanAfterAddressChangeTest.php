<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\CleanAfterAddressChange;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\Quote\AddressFactory;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class CleanAfterAddressChangeTest extends TestCase
{
    public function testCountryChangeResetsShippingAddress(): void
    {
        $address = ObjectManager::getInstance()->get(AddressFactory::class)->create();
        $address->setStreet('Some Street 1');
        $address->setPostcode('1234AB');
        $address->setCity('Amsterdam');
        $address->setShippingMethod('flatrate_flatrate');

        $observer = new Observer([
            'address' => $address,
            'address_type' => 'shipping',
            'field_name' => 'country_id',
        ]);

        $subject = ObjectManager::getInstance()->get(CleanAfterAddressChange::class);
        $subject->execute($observer);

        $this->assertSame('', $address->getCity());
        $this->assertSame('', (string)$address->getPostcode());
        $this->assertSame('', (string)$address->getShippingMethod());

        $request = ObjectManager::getInstance()->get(HttpRequest::class);
        $this->assertTrue((bool)$request->getParam('skip_validation'));
    }

    public function testNonCountryFieldDoesNothing(): void
    {
        $address = ObjectManager::getInstance()->get(AddressFactory::class)->create();
        $address->setCity('Amsterdam');

        $observer = new Observer([
            'address' => $address,
            'address_type' => 'shipping',
            'field_name' => 'city',
        ]);

        $subject = ObjectManager::getInstance()->get(CleanAfterAddressChange::class);
        $subject->execute($observer);

        $this->assertSame('Amsterdam', $address->getCity());
    }
}
