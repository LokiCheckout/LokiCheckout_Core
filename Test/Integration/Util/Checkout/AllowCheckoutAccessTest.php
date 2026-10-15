<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Checkout;

use LokiCheckout\Core\Util\Checkout\AllowCheckoutAccess;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class AllowCheckoutAccessTest extends TestCase
{
    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testAllowWhenEnabled(): void
    {
        $subject = ObjectManager::getInstance()->get(AllowCheckoutAccess::class);
        $this->assertTrue($subject->allow());
    }

    #[ConfigFixture('loki_checkout/general/enable', 0, 'store', 'default')]
    public function testDenyWhenDisabledWithoutLicense(): void
    {
        $subject = ObjectManager::getInstance()->get(AllowCheckoutAccess::class);
        $this->assertFalse($subject->allow());
    }

    #[
        ConfigFixture('loki_checkout/general/enable', 0, 'store', 'default'),
        ConfigFixture('loki_checkout/general/license_key', 'valid-license-key-123', 'store', 'default'),
    ]
    public function testDenyWhenDisabledWithMatchingLicense(): void
    {
        $request = ObjectManager::getInstance()->get(RequestInterface::class);
        $request->setParam('license', 'valid-license-key-123');

        $subject = ObjectManager::getInstance()->get(AllowCheckoutAccess::class);
        $this->assertFalse($subject->allow());
    }

    #[
        ConfigFixture('loki_checkout/general/enable', 0, 'store', 'default'),
        ConfigFixture('loki_checkout/general/license_key', 'valid-license-key-123', 'store', 'default'),
    ]
    public function testDenyWhenDisabledWithWrongLicense(): void
    {
        $request = ObjectManager::getInstance()->get(RequestInterface::class);
        $request->setParam('license', 'wrong-license-key-456');

        $subject = ObjectManager::getInstance()->get(AllowCheckoutAccess::class);
        $this->assertFalse($subject->allow());
    }
}
