<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Config;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Config\Config;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class ConfigTest extends TestCase
{
    public function testIsLokiCheckout()
    {
        $config = $this->getConfig();
        $this->assertTrue($config->isLokiCheckout());
    }

    #[ConfigFixture('yireo_loki_checkout/general/enable', 1)]
    public function testIsEnabled()
    {
        $config = $this->getConfig();
        $this->assertTrue($config->isEnabled());
    }


    #[ConfigFixture('yireo_loki_checkout/general/enable', 0)]
    public function testIsNotEnabled()
    {
        $config = $this->getConfig();
        $this->assertFalse($config->isEnabled());
    }

    #[ConfigFixture('yireo_loki_checkout/general/theme', 'default')]
    public function testGetDefaultTheme()
    {
        $config = $this->getConfig();
        $this->assertEquals('default', $config->getTheme());
    }

    #[ConfigFixture('yireo_loki_checkout/general/theme', 'onestep')]
    public function testGetOneStepTheme()
    {
        $config = $this->getConfig();
        $this->assertEquals('onestep', $config->getTheme());
    }

    #[ConfigFixture('yireo_loki_checkout/general/theme', 'nonexisting')]
    public function testGetNonExistingTheme()
    {
        $config = $this->getConfig();
        $this->assertEquals('default', $config->getTheme());
    }

    #[ConfigFixture('yireo_loki_checkout/general/grid_layout', 'default_grid')]
    public function testGetDefaultGridLayoutName()
    {
        $config = $this->getConfig();
        $this->assertEquals('default_grid', $config->getGridLayoutName());
    }

    #[ConfigFixture('yireo_loki_checkout/general/grid_layout', 'nonexisting')]
    public function testGetNonExistingGridLayoutName()
    {
        $config = $this->getConfig();
        $this->assertEquals('default_grid', $config->getGridLayoutName());
    }


    #[ConfigFixture('yireo_loki_checkout/general/grid_layout', 'default_grid')]
    public function testGetDefaultGridLayout()
    {
        $config = $this->getConfig();
        $this->assertEquals('default_grid', $config->getGridLayout()->getName());
    }

    #[ConfigFixture('yireo_loki_checkout/general/grid_layout', 'default_grid')]
    /**
     * @dataProvider getTestGetGridArguments
     */
    public function testGetGrid(string $countryId, string $expectedGridName)
    {
        $billingAddress = $this->createMock(AddressInterface::class);
        $billingAddress->method('getCountryId')->willReturn($countryId);

        $quote = $this->createMock(Quote::class);
        $quote->method('getShippingAddress')->willReturn($billingAddress);

        $checkoutSession = $this->createMock(Session::class);
        $checkoutSession->method('getQuoteId')->willReturn(42);

        $checkoutState = $this->createMock(CheckoutState::class);
        $checkoutState->method('getCheckoutSession')->willReturn($checkoutSession);
        $checkoutState->method('getQuote')->willReturn($quote);

        $config = $this->getObjectManager()->create(Config::class, [
            'checkoutState' => $checkoutState,
        ]);
        $this->assertEquals($expectedGridName, $config->getGrid()->getName());
    }

    public function getTestGetGridArguments(): array
    {
        return [
            ['US', 'default'],
            ['NL', 'NL'],
            ['BE', 'BE'],
        ];
    }

    #[ConfigFixture('yireo_loki_checkout/debug/enable', 1)]
    public function testIsDebug()
    {
        $config = $this->getConfig();
        $this->assertTrue($config->isDebug());
    }


    #[ConfigFixture('yireo_loki_checkout/debug/enable', 0)]
    public function testIsNotDebug()
    {
        $config = $this->getConfig();
        $this->assertFalse($config->isDebug());
    }

    private function getConfig(): Config
    {
        return $this->getObjectManager()->get(Config::class);
    }

    private function getObjectManager(): ObjectManagerInterface
    {
        return ObjectManager::getInstance();
    }
}
