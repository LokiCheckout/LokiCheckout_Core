<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Component;

use LokiCheckout\Core\Util\Component\AddressStateProvider;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class AddressStateProviderTest extends TestCase
{
    private AddressStateProvider $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(AddressStateProvider::class);
    }

    public function testBillingAddressType(): void
    {
        $name = 'loki-checkout.billing.address.city';

        $this->assertSame('billing', $this->subject->getAddressType($name));
        $this->assertTrue($this->subject->isBillingAddressType($name));
        $this->assertFalse($this->subject->isShippingAddressType($name));
        $this->assertInstanceOf(BillingAddressState::class, $this->subject->getAddressState($name));
    }

    public function testShippingAddressType(): void
    {
        $name = 'loki-checkout.shipping.address.city';

        $this->assertSame('shipping', $this->subject->getAddressType($name));
        $this->assertTrue($this->subject->isShippingAddressType($name));
        $this->assertFalse($this->subject->isBillingAddressType($name));
        $this->assertInstanceOf(ShippingAddressState::class, $this->subject->getAddressState($name));
    }
}
