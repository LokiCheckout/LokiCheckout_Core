<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Config;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Config\Config;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use LokiCheckout\Core\ViewModel\CheckoutState;

class ConfigTest extends TestCase
{
    public function testIsLokiCheckout()
    {
        $config = $this->getConfig();
        $this->assertTrue($config->isLokiCheckout());
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testIsEnabled()
    {
        $config = $this->getConfig();
        $this->assertTrue($config->isEnabled());
    }


    #[ConfigFixture('loki_checkout/general/enable', 0, 'store', 'default')]
    public function testIsNotEnabled()
    {
        $config = $this->getConfig();
        $this->assertFalse($config->isEnabled());
    }

    #[ConfigFixture('loki_checkout/general/theme', 'default', 'store', 'default')]
    public function testGetDefaultTheme()
    {
        $config = $this->getConfig();
        $this->assertEquals('default', $config->getTheme());
    }

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    public function testGetOneStepTheme()
    {
        $config = $this->getConfig();
        $this->assertEquals('onestep', $config->getTheme());
    }

    #[ConfigFixture('loki_checkout/general/theme', 'nonexisting', 'store', 'default')]
    public function testGetNonExistingTheme()
    {
        $config = $this->getConfig();
        $this->assertEquals('default', $config->getTheme());
    }

    #[ConfigFixture('loki_checkout/general/grid_layout', 'default_grid', 'store', 'default')]
    public function testGetDefaultGridLayoutName()
    {
        $config = $this->getConfig();
        $this->assertEquals('default_grid', $config->getGridLayoutName());
    }

    #[ConfigFixture('loki_checkout/general/grid_layout', 'nonexisting', 'store', 'default')]
    public function testGetNonExistingGridLayoutName()
    {
        $config = $this->getConfig();
        $this->assertEquals('default_grid', $config->getGridLayoutName());
    }


    #[ConfigFixture('loki_checkout/general/grid_layout', 'default_grid', 'store', 'default')]
    public function testGetDefaultGridLayout()
    {
        $config = $this->getConfig();
        $this->assertEquals('default_grid', $config->getGridLayout()->getName());
    }

    #[ConfigFixture('loki_checkout/general/grid_layout', 'default_grid', 'store', 'default')]
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
            ['NL', 'netherlands'],
            ['BE', 'belgium'],
        ];
    }

    #[ConfigFixture('loki_checkout/debug/enable', 1, 'store', 'default')]
    public function testIsDebug()
    {
        $config = $this->getConfig();
        $this->assertTrue($config->isDebug());
    }


    #[ConfigFixture('loki_checkout/debug/enable', 0, 'store', 'default')]
    public function testIsNotDebug()
    {
        $config = $this->getConfig();
        $this->assertFalse($config->isDebug());
    }

    #[ConfigFixture('loki_checkout/newsletter/default_value', 1, 'store', 'default')]
    public function testIsNewsletterCheckedByDefault()
    {
        $config = $this->getConfig();
        $this->assertTrue($config->isNewsletterCheckedByDefault());
    }

    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    public function testIsNewsletterNotCheckedByDefault()
    {
        $config = $this->getConfig();
        $this->assertFalse($config->isNewsletterCheckedByDefault());
    }

    private function getConfig(): Config
    {
        return $this->getObjectManager()->get(Config::class);
    }

    /**
     * @return ObjectManagerInterface
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getObjectManager(): ObjectManagerInterface
    {
        return ObjectManager::getInstance();
    }
}
