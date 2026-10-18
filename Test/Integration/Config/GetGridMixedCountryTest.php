<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Config;

use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Config\XmlConfig;
use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout;
use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout\Grid;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GetGridMixedCountryTest extends TestCase
{
    #[DataProvider('getGridArguments')]
    public function testGetGrid(
        string $shippingCountryId,
        string $billingCountryId,
        bool $isVirtual,
        string $addressType,
        string $expectedGridName
    ): void {
        $config = $this->getConfig($shippingCountryId, $billingCountryId, $isVirtual);

        $block = ObjectManager::getInstance()->create(Template::class);
        if (!empty($addressType)) {
            $block->setData('grid_address_type', $addressType);
        }

        $this->assertEquals($expectedGridName, $config->getGrid($block)->getName());
    }

    public static function getGridArguments(): array
    {
        return [
            'shipping form follows shipping country' => ['DE', 'NL', false, 'shipping', 'german'],
            'billing form follows billing country' => ['DE', 'NL', false, 'billing', 'netherlands'],
            'billing form reversed' => ['NL', 'DE', false, 'billing', 'german'],
            'unmarked block follows shipping country' => ['DE', 'NL', false, '', 'german'],
            'empty billing country falls back to shipping' => ['DE', '', false, 'billing', 'german'],
            'unknown billing country falls back to default grid' => ['DE', 'US', false, 'billing', 'default'],
            'virtual quote follows billing country' => ['', 'NL', true, 'shipping', 'netherlands'],
        ];
    }

    private function getConfig(string $shippingCountryId, string $billingCountryId, bool $isVirtual): Config
    {
        $shippingAddress = $this->createMock(Address::class);
        $shippingAddress->method('getCountryId')->willReturn($shippingCountryId);

        $billingAddress = $this->createMock(Address::class);
        $billingAddress->method('getCountryId')->willReturn($billingCountryId);

        $quote = $this->createMock(Quote::class);
        $quote->method('isVirtual')->willReturn($isVirtual);
        $quote->method('getShippingAddress')->willReturn($shippingAddress);
        $quote->method('getBillingAddress')->willReturn($billingAddress);

        $checkoutSession = $this->createMock(Session::class);
        $checkoutSession->method('getQuoteId')->willReturn(42);

        $checkoutState = $this->createMock(CheckoutState::class);
        $checkoutState->method('getCheckoutSession')->willReturn($checkoutSession);
        $checkoutState->method('getQuote')->willReturn($quote);

        $gridLayout = new GridLayout('default_grid', 'Default', 99, 6, 6, [
            new Grid('default', '', '', 99, 6, 6),
            new Grid('german', 'DE', 'default', 99, 6, 6),
            new Grid('netherlands', 'NL', 'default', 99, 6, 6),
        ]);

        $xmlConfig = $this->createMock(XmlConfig::class);
        $xmlConfig->method('getGridLayouts')->willReturn(['default_grid' => $gridLayout]);
        $xmlConfig->method('getGridLayout')->willReturn($gridLayout);

        return ObjectManager::getInstance()->create(Config::class, [
            'checkoutState' => $checkoutState,
            'xmlConfig' => $xmlConfig,
        ]);
    }
}
